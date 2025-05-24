<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;

class RegistrationController extends Controller
{

    public function show()
    {
        $registrations = Registration::orderBy('created_at', 'DESC')->get();


        return inertia('Registration/Index', [
            'registrations' => $registrations,
        ]);
    }

    public function showRegistration(Registration $registration)
    {
        if (!$registration) {
            return redirect()->route('admin.registrations')->with('error', 'Registration not found');
        }

        // Refresh the registration instance to get the latest data

        return inertia('Registration/Details', [
            'registration' => $registration,
        ]);
    }

    public function updateStatus(Request $request, $registrationId)
    {
        $registration = Registration::find($registrationId);


        $request->validate([
            'interview_status' => 'required|in:pending,checked,scheduled,accepted,rejected',
        ]);

        if (!$registration) {
            return redirect()->route('admin.registrations')->with('error', 'Registration not found');
        }

        $registration->update([
            'interview_status' => $request->input('interview_status'),
        ]);

        return redirect()->back()->with('success', 'Interview status updated successfully');
    }
}
