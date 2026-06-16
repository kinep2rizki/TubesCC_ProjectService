<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Services\UserService;

class ParticipantController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $participants = EventParticipant::where('event_id', $eventId)->get();
        
        // Data Stitching
        $userIds = $participants->pluck('user_id')->unique()->toArray();
        $usersData = UserService::getUsersBatch($userIds);

        $participants->transform(function ($participant) use ($usersData) {
            $participant->user_detail = $usersData[$participant->user_id] ?? null;
            return $participant;
        });

        return response()->json(['success' => true, 'data' => $participants], 200);
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // Ambil ID dari token JWT
        $userId = $request->auth_user_id;

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $participant = EventParticipant::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => $userId,
        ], [
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
            'status' => 'Registered'
        ]);

        $userData = UserService::getUser($userId);
        $participant->user_detail = $userData;

        return response()->json([
            'success' => true, 
            'message' => 'Successfully registered to event', 
            'data' => $participant
        ], 201);
    }

    public function export($eventId)
    {
        return response()->json(['success' => true, 'message' => 'Export initiated'], 200);
    }
}
