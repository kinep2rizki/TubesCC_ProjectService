<?php

namespace App\Http\Controllers;

use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index($eventId)
    {
        return view('Pages.Attendance');
    }

    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'ticket_number' => 'required|string',
            'method' => 'required|string', // QR or Manual
        ]);

        // Logic to record attendance
        // Attendance::create([...]);

        return back()->with('success', 'Participant checked in successfully.');
    }
}
