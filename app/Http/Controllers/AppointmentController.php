<?php

namespace App\Http\Controllers; // Adjust namespace if needed

use App\Http\Controllers\Controller;
use App\Models\AppointmentAvailability;
use App\Models\Registration; // Import Registration model
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // For logging errors
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable; // Import Throwable for catching exceptions

class AppointmentController extends Controller
{
    /**
     * Display a listing of ALL appointment slots for the admin.
     * Now includes booking information.
     */
    public function index()
    {

        // Eager load registration details (id and name) if a registration exists
        $allSlotsRaw = AppointmentAvailability::with('registration:id') // Select only needed columns
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $allSlotsGrouped = $allSlotsRaw
            ->groupBy(fn($item) => $item->date->format('Y-m-d'))
            ->map(function ($group) {
                return $group->map(fn($slot) => [
                    'id' => $slot->id, // Pass slot ID
                    'time' => $slot->time->format('H:i'),
                    'registration_id' => $slot->registration_id,
                    // Access eager loaded relationship safely
                    'registration_name' => Registration::find($slot->registration_id)->student_full_name ?? null,
                ])->values()->toArray(); // Use values() if you don't need original keys
            });

        return Inertia::render('Appointments/Index', [
            'allSlotsGrouped' => $allSlotsGrouped, // Send single prop
        ]);
    }

    /**
     * Show the form for creating/editing availability (Admin).
     */
    public function create()
    {
        // Fetch ALL existing availability to potentially pre-fill the form
        $existingRaw = AppointmentAvailability::orderBy('date')->orderBy('time')->get();

        // Group for potential display/editing purposes (only times needed here)
        $existingGrouped = $existingRaw->groupBy(fn($item) => $item->date->format('Y-m-d'))
            ->map(fn($group) => $group->pluck('time')->map->format('H:i')->toArray());

        // Route name updated due to grouping in web.php
        return Inertia::render('Appointments/CreateEditAppointment', [
            'existingAvailability' => $existingGrouped
        ]);
    }

    /**
     * Store newly created/updated availability (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'availability' => ['required', 'array'],
            // Validate keys are dates
            'availability.*' => ['required', 'array'],
            // Validate values are times
            'availability.*.*' => ['required', 'date_format:H:i'],
        ]);

        // Additional validation for date keys (optional but good)
        foreach (array_keys($validated['availability']) as $dateKey) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateKey) || !Carbon::createFromFormat('Y-m-d', $dateKey)) {
                throw ValidationException::withMessages(['availability' => "Formato de fecha inválido ('{$dateKey}'). Use YYYY-MM-DD."]);
            }
        }

        $submittedDates = array_keys($validated['availability']);
        if (empty($submittedDates)) {
            return Redirect::back()->with('warning', 'No se proporcionaron fechas válidas.');
        }

        DB::beginTransaction();
        try {
            // Delete existing slots ONLY IF THEY ARE NOT BOOKED for the submitted dates
            // This prevents accidentally deleting booked appointments when updating schedule
            AppointmentAvailability::whereIn('date', $submittedDates)
                ->whereNull('registration_id') // Only delete unbooked slots
                ->delete();

            $inserts = [];
            $now = now();
            $existingSlots = AppointmentAvailability::whereIn('date', $submittedDates)
                ->get(['date', 'time'])
                ->keyBy(fn($slot) => $slot->date->format('Y-m-d') . '|' . $slot->time->format('H:i')); // Key by "date|time"

            foreach ($validated['availability'] as $date => $times) {
                foreach ($times as $time) {
                    // Only insert if the slot doesn't already exist (booked or otherwise)
                    if (!$existingSlots->has($date . '|' . $time)) {
                        $inserts[] = [
                            'date' => $date,
                            'time' => $time,
                            'registration_id' => null, // Explicitly null
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                    }
                }
            }

            if (!empty($inserts)) {
                AppointmentAvailability::insert($inserts);
            }

            DB::commit();
            // Route name updated due to grouping in web.php
            return Redirect::route('admin.appointments')
                ->with('success', 'Disponibilidad guardada correctamente.');
        } catch (Throwable $e) { // Catch Throwable for wider error catching
            DB::rollBack();
            Log::error("Error saving availability: " . $e->getMessage()); // Log the error
            return Redirect::back()->withInput()->with('error', 'Error al guardar la disponibilidad. ' . $e->getMessage());
        }
    }

    // --- NEW METHODS FOR USER APPOINTMENT SELECTION ---

    /**
     * Display the appointment selection page for a specific registration.
     * Fetches only future, available slots.
     */
    public function showForUser(Registration $registration) // Use Route Model Binding
    {
        // Optional: Add checks here, e.g., if registration status allows booking
        // if ($registration->status !== 'pending_appointment') {
        //     return Redirect::route('some.error.page')->with('error', 'Registration not ready for appointment.');
        // }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        // Optional: Check if the registration is already booked
        if ($registration->interview_status === 'scheduled') {
            $selectedDate = AppointmentAvailability::where('registration_id', $registration->id)
                ->first(['date', 'time']);
            if ($selectedDate) {
                $formattedDate = Carbon::parse($selectedDate->date)->format('d/m/Y');
                $formattedTime = Carbon::parse($selectedDate->time)->format('H:i');
                return Redirect::route('inscription.successful') // Or a dedicated 'booking confirmed' page
                    ->with('successmk', 'Ya tienes una cita reservada para el ' . $formattedDate . ' a las ' . $formattedTime . '.');
            }
            return Redirect::route('inscription.successful') // Or a dedicated 'booking confirmed' page
                ->with('success', 'Cita reservada con éxito.');
        }

        // Fetch only AVAILABLE slots (registration_id is null)
        // AND only slots in the future (or today with time later than now)
        $availableSlotsRaw = AppointmentAvailability::whereNull('registration_id')
            ->where(function ($query) use ($today, $now) {
                $query->where('date', '>', $today) // Future dates
                    ->orWhere(function ($query) use ($today, $now) {
                        $query->where('date', $today) // Today
                            ->where('time', '>', $now); // Time later than now
                    });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        // Group by date and format the times (only time is needed for the value)
        $availabilityGrouped = $availableSlotsRaw
            ->groupBy(fn($item) => $item->date->format('Y-m-d')) // Group by date string
            ->map(function ($group) {
                // For each date group, get the times formatted as HH:mm
                return $group->pluck('time')->map(fn($time) => $time->format('H:i'))->toArray();
            })
            // Ensure only dates with times are included
            ->filter(fn($times) => !empty($times));

        // Pass the grouped data AND the registration ID to the Inertia view
        return Inertia::render('Inscription/Appointment', [
            'availabilityGrouped' => $availabilityGrouped,
            'registrationId' => $registration->id, // Pass the UUID
        ]);
    }

    /**
     * Book an appointment slot for the given registration.
     */
    public function bookForUser(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $selectedDate = $validated['date'];
        $selectedTime = $validated['time'];

        DB::beginTransaction();
        try {
            // Find the specific slot and lock it for update to prevent race conditions
            $slot = AppointmentAvailability::where('date', $selectedDate)
                ->where('time', $selectedTime)
                ->lockForUpdate() // Pessimistic lock
                ->first();

            // Check 1: Did the slot exist?
            if (!$slot) {
                throw ValidationException::withMessages([
                    'slot' => 'El horario seleccionado ya no existe.',
                ]);
            }

            // Check 2: Is the slot still available?
            if ($slot->registration_id !== null) {
                // Check if it's already booked by the *same* registration (maybe allow re-booking?)
                // if ($slot->registration_id === $registration->id) {
                // Handle case where user tries to book the same slot again - maybe redirect to success?
                // DB::commit(); // Commit transaction as no change is needed
                // return Redirect::route('inscription.successful')->with('info', 'Ya tienes esta cita reservada.');
                // } else {
                // Booked by someone else
                throw ValidationException::withMessages([
                    'slot' => 'El horario seleccionado acaba de ser reservado. Por favor, elige otro.',
                ]);
                // }
            }

            // Check 3: Does this registration already have a booking? Prevent double booking.
            $existingBooking = AppointmentAvailability::where('registration_id', $registration->id)->exists();
            if ($existingBooking) {
                throw ValidationException::withMessages([
                    'booking' => 'Ya tienes una cita reservada.',
                ]);
                // Or maybe update the existing one? Depends on business logic.
                // For now, prevent double booking.
            }

            $registration->interview_status = 'scheduled'; // Update registration status
            $registration->save();
            // If all checks pass, book the slot
            $slot->registration_id = $registration->id;
            $slot->save();

            // Optional: Update registration status
            // $registration->status = 'appointment_booked';
            // $registration->save();

            DB::commit();

            // Redirect to success page
            return Redirect::route('inscription.successful') // Or a dedicated 'booking confirmed' page
                ->with('success', 'Cita reservada con éxito para el ' . Carbon::parse($selectedDate)->format('d/m/Y') . ' a las ' . $selectedTime . '.');
        } catch (ValidationException $e) {
            DB::rollBack();
            // Redirect back with validation errors
            return Redirect::back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Error booking appointment for registration {$registration->id}: " . $e->getMessage());
            // Redirect back with a general error message
            return Redirect::back()->with('error', 'Ocurrió un error al intentar reservar la cita. Por favor, inténtalo de nuevo.');
        }
    }
}
