<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InscriptionNewController;
use App\Http\Controllers\InscriptionRecurrentController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// --- Debugging Route for PHP Info (Remove in production) ---
Route::get('/my-very-secret-phpinfo-check-route-for-real-this-time', function () {
    phpinfo();
});

// --- Public Routes ---
Route::get('/', function () {
    return Inertia::render('Welcome/Welcome');
})->name('home');

Route::get('inscription', function () {
    return Inertia::render('Inscription');
})->name('inscription');

// New User Inscription Flow
Route::get('inscription/new', function () {
    return Inertia::render('Inscription/New');
})->name('inscription.new');
Route::post('inscription/new', [InscriptionNewController::class, 'store'])->name('inscription.new.post');

// Recurrent User Inscription Flow (Assuming this leads to appointment selection eventually)
Route::get('inscription/recurrent', function () {
    return Inertia::render('Inscription/Recurrent');
})->name('inscription.recurrent');
Route::post('inscription/recurrent', [InscriptionRecurrentController::class, 'store'])->name('inscription.recurrent.post');

// Appointment Selection Flow (Now requires Registration ID)
// The route you mentioned: inscription/appointment/registerID
Route::get('inscription/appointment/{registration:id}', [AppointmentController::class, 'showForUser']) // Renamed registrationId to registration for route model binding
    ->name('inscription.appointment.show'); // Renamed route for clarity

// Route to handle the POST request when a user selects an appointment
Route::post('inscription/appointment/{registration:id}', [AppointmentController::class, 'bookForUser'])
    ->name('inscription.appointment.book'); // New route name for booking

// Success Page
Route::get('inscription/successful', function () {
    return Inertia::render('Inscription/Successful');
})->name('inscription.successful');


// --- Admin Routes (Auth Protected) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () { // Added prefix and name grouping
    Route::get('/',  [DashboardController::class, "show"])->name('dashboard'); // Changed name to avoid conflict

    // Registrations
    Route::get('registrations', [RegistrationController::class, "show"])->name('registrations');
    Route::get('registrations/{registration:id}', [RegistrationController::class, "showRegistration"])->name('registrations.show'); // Use uuid here too? Assuming ID for now based on original code. Adjust if needed.
    Route::put('registrations/{registration:id}/status', [RegistrationController::class, "updateStatus"])->name('registrations.updateStatus'); // Use uuid here too?

    // Appointments (Admin Management)
    Route::get('appointments', [AppointmentController::class, 'index'])
        ->name('appointments'); // Route name is now admin.appointments

    Route::get('appointments/create', [AppointmentController::class, 'create'])
        ->name('appointments.create'); // Route name is now admin.appointments.create

    // *** FIXED TYPO: 'apointment' -> 'appointment' ***
    Route::post('appointments', [AppointmentController::class, 'store'])
        ->name('appointments.store'); // Route name is now admin.appointments.store
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
