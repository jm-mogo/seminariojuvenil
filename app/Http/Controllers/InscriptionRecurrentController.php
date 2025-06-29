<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecurrentRegistrationRequest;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

class InscriptionRecurrentController extends Controller
{
    /**
     * Store a newly created recurrent registration in storage.
     *
     * @param StoreRecurrentRegistrationRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRecurrentRegistrationRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // --- Define fileFields BEFORE the try block ---
        $fileFields = [
            'profilePhoto' => 'doc_photo_path',
            'studentIdPhoto' => 'doc_student_id_card_path',
            'guardianIdPhoto' => 'doc_guardian_id_card_path',
            'recommendationLetter' => 'doc_recommendation_path'
        ];
        // --- End definition ---

        $filePaths = []; // To store paths of *successfully* saved files
        $baseStoragePath = 'registrations';
        $registrationSpecificPath = null; // Initialize path variable

        try {
            DB::beginTransaction();

            // 1. Prepare data for insertion
            $registrationData = [
                'registration_type' => 'recurrent',
                'interview_status' => 'pending',
                'payment_status' => 'pending',
                'student_full_name' => $validatedData['studentFirstName'] . ' ' . $validatedData['studentLastName'],
                'student_date_of_birth' => $validatedData['birthDate'],
                'student_gender' => $validatedData['studentGender'],
                'student_identity_document_number' => $validatedData['studentIdNumber'],
                'student_phone' => $validatedData['studentPhoneNumber'] ?? null,
                'student_email' => $validatedData['studentEmail'] ?? null,
                'guardian_full_name' => $validatedData['guardianFirstName'] . ' ' . $validatedData['guardianLastName'],
                'guardian_phone' => $validatedData['guardianPhoneNumber'],
                'guardian_relationship' => $validatedData['guardianRelationship'],
                'church_name' => $validatedData['churchName'],
                'church_pastor_name' => $validatedData['pastorName'],
                'church_attendance_duration' => $validatedData['attendanceDuration'],
                'previous_participations_count' => $validatedData['previousParticipations'],
                'doc_testimony_path' => null,
                'doc_purpose_statement_path' => null,
            ];

            // 2. Create the initial registration record
            $registration = Registration::create($registrationData);

            // 3. Handle File Uploads (Now that we have the ID)
            $registrationSpecificPath = "{$baseStoragePath}/{$registration->id}"; // Assign path here

            // Use the pre-defined $fileFields array
            foreach ($fileFields as $requestField => $dbColumn) {
                if ($request->hasFile($requestField)) {
                    // Store the file and add the path to $filePaths for potential cleanup on error
                    $path = $request->file($requestField)->store($registrationSpecificPath);
                    $filePaths[$dbColumn] = $path; // Store path for update AND potential rollback cleanup
                }
                // No 'else' needed as we only update with paths that were successfully stored
            }

            // 4. Update the registration record with actual file paths
            if (!empty($filePaths)) {
                // Only update the columns for which files were successfully saved
                $registration->update($filePaths);
            }

            // 5. Commit the transaction
            DB::commit();

            return redirect()->route('inscription.successful')->with('success', '¡Re-inscripción enviada con éxito!');
        } catch (\Throwable $e) {
            DB::rollBack();

            // $fileFields is now defined, so this is safe
            Log::error('Error storing recurrent registration: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(array_keys($fileFields)) // Use defined $fileFields
            ]);

            // Clean up only the files that were *actually* stored in this attempt (tracked in $filePaths)
            foreach ($filePaths as $column => $path) { // Iterate over actually stored paths
                if ($path && Storage::exists($path)) {
                    Storage::delete($path);
                    Log::info("Cleaned up failed upload file: {$path}"); // Optional: Log cleanup
                }
            }
            // Attempt to delete the directory if it exists and is empty
            if ($registrationSpecificPath && Storage::exists($registrationSpecificPath)) {
                // Check if directory is empty after potential file deletions
                if (empty(Storage::files($registrationSpecificPath)) && empty(Storage::directories($registrationSpecificPath))) {
                    Storage::deleteDirectory($registrationSpecificPath);
                    Log::info("Cleaned up empty directory: {$registrationSpecificPath}"); // Optional: Log cleanup
                }
            }


            // $fileFields is now defined, so this is safe
            return redirect()->back()
                ->withInput($request->except(array_keys($fileFields))) // Use defined $fileFields
                ->with('error', 'Ocurrió un error al procesar la re-inscripción. Por favor, inténtalo de nuevo o contacta al soporte.');
        }
    }
}
