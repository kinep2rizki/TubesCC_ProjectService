<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Services\UserService;
use App\Traits\CommunityAuthorization;

class AttendanceController extends Controller
{
    use CommunityAuthorization;

    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);

        $attendances = Attendance::with(['participant'])
            ->whereHas('participant', function($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })->latest()->paginate(15);
            
        // Data Stitching for participants
        $userIds = $attendances->pluck('participant.user_id')->filter()->unique()->toArray();
        $usersData = UserService::getUsersBatch($userIds);

        $attendances->getCollection()->transform(function ($attendance) use ($usersData) {
            if ($attendance->participant) {
                $attendance->participant->user_detail = $usersData[$attendance->participant->user_id] ?? null;
            }
            return $attendance;
        });

        $presentCount = Attendance::whereHas('participant', function($query) use ($eventId) {
            $query->where('event_id', $eventId);
        })->count();
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
        $request->validate(['user_id' => 'required|integer']);
        $userId = $request->user_id;

        $event = Event::findOrFail($eventId);

        // Jika user bukan check in diri sendiri, maka dia harus Owner/Moderator
        if ($userId != $request->auth_user_id) {
            $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);
        }

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
