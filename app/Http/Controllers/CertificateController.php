<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller
{
    public function index($eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);
        
        $activeCommunityId = session('active_community_id');
        if ($event->community_id != $activeCommunityId) {
            return redirect()->route('events')->with('error', 'The event belongs to a different community.');
        }

        $allEvents = \App\Models\Event::where('community_id', $activeCommunityId)->get();
        $participants = \App\Models\EventParticipant::with('user')
            ->where('event_id', $eventId)
            ->where('status', 'Attended')
            ->get();
            
        return view('Pages.Certificate', compact('event', 'allEvents', 'participants'));
    }

    public function generate(Request $request, $eventId)
    {
        $validated = $request->validate([
            'template' => 'required|string',
        ]);

        $event = \App\Models\Event::findOrFail($eventId);
        
        // Fetch all attended participants
        $participants = \App\Models\EventParticipant::with('user')
            ->where('event_id', $eventId)
            ->where('status', 'Attended')
            ->get();

        foreach ($participants as $participant) {
            // Check if certificate already exists
            $certificate = \App\Models\Certificate::firstOrCreate(
                ['event_participant_id' => $participant->id],
                [
                    'template_style' => $validated['template'],
                    'file_url' => '/certificates/dummy-certificate.pdf', // Dummy simulated URL for now
                    'issued_at' => now(),
                ]
            );

            // Send notification to user
            if ($participant->user) {
                $participant->user->notify(new \App\Notifications\CertificateGeneratedNotification($event, $certificate));
            }
        }

        return back()->with('success', 'Certificates generated successfully.');
    }

    public function download($certificateId)
    {
        // Logic to download a specific certificate PDF
        return response()->download('path/to/certificate.pdf');
    }
}
