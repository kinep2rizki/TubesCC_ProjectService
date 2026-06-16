<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\EventParticipant;
use App\Services\UserService;

class AttendanceController extends Controller
{
    public function index($eventId)
    {
        $attendances = Attendance::with(['participant'])
            ->whereHas('participant', function($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })->latest()->get();
            
        // Data Stitching for participants
        $userIds = $attendances->pluck('participant.user_id')->filter()->unique()->toArray();
        $usersData = UserService::getUsersBatch($userIds);

        $attendances->transform(function ($attendance) use ($usersData) {
            if ($attendance->participant) {
                $attendance->participant->user_detail = $usersData[$attendance->participant->user_id] ?? null;
            }
            return $attendance;
        });

        $presentCount = $attendances->count();
        $expectedCount = EventParticipant::where('event_id', $eventId)->count();
        
        return response()->json([
            'success' => true,
            'data' => $attendances,
            'stats' => [
                'presentCount' => $presentCount,
                'expectedCount' => $expectedCount
            ]
        ], 200);
    }

    public function checkIn(Request $request, $eventId)
    {
        // Require user_id since we don't have local email records anymore
        $request->validate(['user_id' => 'required|integer']);
        $userId = $request->user_id;

        $participant = EventParticipant::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'User is not registered for this event.'], 404);
        }

        $attendance = Attendance::firstOrCreate([
            'event_participant_id' => $participant->id,
        ], [
            'check_in_time' => now(),
            'check_in_method' => 'Manual'
        ]);

        $participant->update(['status' => 'Attended']);

        return response()->json([
            'success' => true, 
            'message' => 'Participant checked in successfully', 
            'data' => $attendance
        ], 200);
    }
}
