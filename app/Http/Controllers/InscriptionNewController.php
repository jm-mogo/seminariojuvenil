<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewRegistrationRequest; // Use the form request
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // For Str::uuid() if not using HasUuids trait directly on primary 'id' key

class InscriptionNewController extends Controller
{
    /**
     * Store a newly created registration in storage.
     *
     * @param StoreNewRegistrationRequest $request
     * @return RedirectResponse
     */
    public function store(StoreNewRegistrationRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        // If your primary key is 'id' and uses HasUuids trait, UUID is handled automatically.
        // If your primary key is different (e.g., 'uuid'), generate it manually if needed:
        // $registrationId = Str::uuid();

        // Define the base path for storing registration documents
        // Using the request timestamp + random string for uniqueness before we have the ID/UUID
        // Or, better, if using UUIDs on 'id', it's available after create. Let's adjust.
        $filePaths = [];
        $baseStoragePath = 'public/registrations'; // Store in storage/app/public/registrations

        // Use a database transaction for atomicity
        try {
            DB::beginTransaction();

            // 1. Prepare data for insertion (combine names, set defaults)
            $registrationData = [
                'registration_type' => 'new',
                'status' => 'submitted', // Mark as submitted now
                'payment_status' => 'pending',
                'student_full_name' => $validatedData['studentFirstName'] . ' ' . $validatedData['studentLastName'],
                'student_date_of_birth' => $validatedData['birthDate'],
                'student_gender' => $validatedData['studentGender'], // Get from validated data
                'student_identity_document_number' => $validatedData['studentIdNumber'],
                'student_phone' => $validatedData['studentPhoneNumber'],
                'student_email' => $validatedData['studentEmail'],
                'guardian_full_name' => $validatedData['guardianFirstName'] . ' ' . $validatedData['guardianLastName'],
                // 'guardian_identity_document_number' => $validatedData['guardianIdNumber'], // Not in schema
                'guardian_phone' => $validatedData['guardianPhoneNumber'],
                'guardian_relationship' => $validatedData['guardianRelationship'],
                'church_name' => $validatedData['churchName'],
                // 'church_city' => $validatedData['churchCity'], // Not in schema
                'church_pastor_name' => $validatedData['pastorName'],
                'church_attendance_duration' => $validatedData['attendanceDuration'],
                'previous_participations_count' => 0, // Default for new registration
                // Paths will be added after creation and file storage
            ];

            // 2. Create the initial registration record
            $registration = Registration::create($registrationData);

            // 3. Handle File Uploads (now that we have the registration ID/UUID)
            $registrationSpecificPath = "{$baseStoragePath}/{$registration->id}"; // Use the actual ID/UUID

            $fileFields = [
                'profilePhoto' => 'doc_photo_path',
                'studentIdPhoto' => 'doc_student_id_card_path',
                'guardianIdPhoto' => 'doc_guardian_id_card_path',
                'salvationTestimony' => 'doc_testimony_path',
                'enrollmentPurpose' => 'doc_purpose_statement_path',
                'recommendationLetter' => 'doc_recommendation_path',
            ];

            foreach ($fileFields as $requestField => $dbColumn) {
                if ($request->hasFile($requestField)) {
                    // Store the file and get the relative path (e.g., 'public/registrations/uuid/filename.jpg')
                    $path = $request->file($requestField)->store($registrationSpecificPath);

                    // Remove the visibility prefix ('public/') for storing in DB if needed,
                    // or adjust based on how you retrieve files later.
                    // Storage::url($path) will correctly generate URLs if stored under 'public'.
                    $filePaths[$dbColumn] = $path; // Store the full path returned by store()
                } else {
                    $filePaths[$dbColumn] = null;
                }
            }

            // 4. Update the registration record with file paths
            if (!empty($filePaths)) {
                $registration->update($filePaths);
            }

            // 5. Commit the transaction
            DB::commit();

            // Redirect back with a success message (user-facing text in Spanish)
            return redirect()->route('inscription.successful')->with('success', '¡Inscripción enviada con éxito! Nos pondremos en contacto pronto.');
        } catch (\Throwable $e) {
            // 6. Rollback transaction on error
            DB::rollBack();

            // Log the detailed error for debugging
            Log::error('Error storing registration: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(array_keys($fileFields)) // Don't log file contents
            ]);

            // Clean up any potentially stored files if the DB transaction failed
            // This part can be complex; consider a more robust cleanup strategy if needed
            foreach ($filePaths as $path) {
                if ($path && Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
            // Also delete the directory if it was created and is now empty
            if (isset($registrationSpecificPath) && Storage::exists($registrationSpecificPath) && empty(Storage::files($registrationSpecificPath)) && empty(Storage::directories($registrationSpecificPath))) {
                Storage::deleteDirectory($registrationSpecificPath);
            }


            // Redirect back with a generic error message (user-facing text in Spanish)
            // It's often better not to expose specific system errors to the user.
            return redirect()->back()
                ->withInput($request->except(array_keys($fileFields))) // Send back input except files
                ->with('error', 'Ocurrió un error al procesar la inscripción. Por favor, inténtalo de nuevo o contacta al soporte.');
        }
    }
}
