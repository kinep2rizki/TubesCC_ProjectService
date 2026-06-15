<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use App\Models\Event;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        $activeCommunityId = session('active_community_id');
        if ($event->community_id != $activeCommunityId) {
            return redirect()->route('events')->with('error', 'The event belongs to a different community.');
        }

        $query = EventParticipant::with('user')->where('event_id', $eventId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $participants = $query->paginate(10);
        return view('Pages.ParticipantsPage', compact('event', 'participants'));
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        if (!auth()->user()->canManageParticipants($event->community_id)) {
            abort(403, 'Unauthorized to add participants.');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'status' => 'required|in:Registered,Attended,Waitlisted',
        ]);

        $user = \App\Models\User::firstOrCreate(
            ['email' => $validated['email']],
            ['name' => $validated['name'], 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );

        EventParticipant::firstOrCreate([
            'event_id' => $eventId,
            'user_id' => $user->id,
        ], [
            'status' => $validated['status']
        ]);

        return redirect()->route('participants', $eventId)->with('success', 'Participant added successfully.');
    }

    public function update(Request $request, $eventId, $participantId)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Registered,Attending,Not Attending'
        ]);

        $participant = \App\Models\EventParticipant::where('event_id', $eventId)->findOrFail($participantId);
        $participant->update([
            'status' => $validated['status']
        ]);

        return redirect()->route('participants', $eventId)->with('success', 'Participant status updated successfully.');
    }

    public function export(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        if (!auth()->user()->canManageParticipants($event->community_id)) {
            abort(403, 'Unauthorized to export participants.');
        }

        $query = EventParticipant::with('user')->where('event_id', $eventId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $participants = $query->get();

        $fileName = 'participants_event_' . $eventId . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Name', 'Email', 'Status', 'Registration Date'];

        $callback = function() use($participants, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($participants as $participant) {
                $row['Name']  = $participant->user->name ?? 'Unknown';
                $row['Email'] = $participant->user->email ?? 'N/A';
                $row['Status']  = $participant->status;
                $row['Registration Date']  = $participant->created_at->format('Y-m-d H:i:s');

                fputcsv($file, array($row['Name'], $row['Email'], $row['Status'], $row['Registration Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
