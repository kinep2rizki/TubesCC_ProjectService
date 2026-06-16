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
        $event = Event::findOrFail($eventId);

        if ($request->filled('email')) {
            // Manual Check-in by Admin via email
            $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);
            
            $userData = UserService::findUserByEmail($request->email);
            if (!$userData) {
                return response()->json(['success' => false, 'message' => 'User not found in Auth Service.'], 404);
            }
            $userId = $userData['id'];

            $participant = EventParticipant::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->first();

        } elseif ($request->filled('ticket_number')) {
            // Check-in via QR Code Scanner
            $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);
            
            $participant = EventParticipant::where('event_id', $eventId)
                ->where('ticket_number', $request->ticket_number)
                ->first();

        } else {
            // Self Check-in or Check-in by ID
            $request->validate(['user_id' => 'required|integer']);
            $userId = $request->user_id;

            if ($userId != $request->auth_user_id) {
                $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);
            }

            $participant = EventParticipant::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->first();
        }

        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Participant is not registered for this event.'], 404);
        }

        if ($participant->status === 'Attended') {
            return response()->json(['success' => false, 'message' => 'Participant has already checked in.'], 400);
        }

        $attendance = Attendance::firstOrCreate([
            'event_participant_id' => $participant->id,
        ], [
            'check_in_time' => now(),
            'check_in_method' => $request->filled('ticket_number') ? 'QR Scan' : 'Manual'
        ]);

        $participant->update(['status' => 'Attended']);

        // Calculate counts for broadcasting
        $presentCount = Attendance::whereHas('participant', function($query) use ($eventId) {
            $query->where('event_id', $eventId);
        })->count();
        $expectedCount = EventParticipant::where('event_id', $eventId)->count();

        // Broadcast the update
        broadcast(new \App\Events\LiveAttendanceUpdated($eventId, $presentCount, $expectedCount));

        return response()->json([
            'success' => true, 
            'message' => 'Participant checked in successfully', 
            'data' => $attendance
        ], 200);
    }
}
