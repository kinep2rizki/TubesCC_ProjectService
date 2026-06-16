<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Services\UserService;
use App\Traits\CommunityAuthorization;

class ParticipantController extends Controller
{
    use CommunityAuthorization;

    public function index(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);
        
        $query = EventParticipant::where('event_id', $eventId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $matchingUserIds = UserService::searchUsers($request->search);
            if (!empty($matchingUserIds)) {
                $query->whereIn('user_id', $matchingUserIds);
            } else {
                // Force empty result if search doesn't match any user
                $query->where('id', -1);
            }
        }

        $participants = $query->paginate(15);
        
        // Data Stitching
        $userIds = $participants->pluck('user_id')->unique()->toArray();
        $usersData = UserService::getUsersBatch($userIds);

        $participants->getCollection()->transform(function ($participant) use ($usersData) {
            $participant->user_detail = $usersData[$participant->user_id] ?? null;
            return $participant;
        });

        return response()->json(['success' => true, 'data' => $participants], 200);
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        if ($request->filled('email') && $request->filled('name')) {
            // Manual Add by Admin
            $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);
            
            $userData = UserService::findOrCreateUser($request->email, $request->name);
            if (!$userData) {
                return response()->json(['success' => false, 'message' => 'Failed to resolve user.'], 500);
            }
            $userId = $userData['id'];
            $status = $request->input('status', 'Registered');
        } else {
            // Self Join
            $userId = $request->auth_user_id;
            $status = 'Registered';

            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
        }

        $participant = EventParticipant::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => $userId,
        ], [
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
            'status' => $status
        ]);

        $participant->user_detail = UserService::getUser($userId);

        return response()->json([
            'success' => true, 
            'message' => 'Successfully registered to event', 
            'data' => $participant
        ], 201);
    }

    public function update(Request $request, $eventId, $participantId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Moderator']);

        $request->validate(['status' => 'required|string']);

        $participant = EventParticipant::where('event_id', $eventId)->findOrFail($participantId);
        $participant->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function export($eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeCommunityAccess($event->community_id, ['Owner']);

        return response()->json(['success' => true, 'message' => 'Export initiated'], 200);
    }
}
