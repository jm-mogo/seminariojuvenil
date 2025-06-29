<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show(Request $request)
    {

        $registrations = Registration::all();
        $totalRegistrations = $registrations->count();
        $newRegistrations = $registrations->where('registration_type', 'new')->count();
        $recurrentRegistrations = $registrations->where('registration_type', 'recurrent')->count();

        $pendingRegistrations = $registrations->where('interview_status', 'pending')->count();
        $pendingScheduledInterviews = $registrations->where('interview_status', 'checked')->count();
        $scheduledInterviews = $registrations->where('interview_status', 'scheduled')->count();

        $girlRegistrations = $registrations->where('student_gender', 'female')->count();
        $boyRegistrations = $registrations->where('student_gender', 'male')->count();


        return inertia('Dashboard', [
            'totalRegistrations' => $totalRegistrations,
            'newRegistrations' => $newRegistrations,
            'recurrentRegistrations' => $recurrentRegistrations,
            'pendingRegistrations' => $pendingRegistrations,
            'pendingScheduledInterviews' => $pendingScheduledInterviews,
            'scheduledInterviews' => $scheduledInterviews,
            'girlRegistrations' => $girlRegistrations,
            'boyRegistrations' => $boyRegistrations
        ]);
    }
}
