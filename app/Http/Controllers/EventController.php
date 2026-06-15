<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $activeCommunityId = session('active_community_id');

        // Calculate real stats from the database for the active community
        $totalEvents = \App\Models\Event::where('community_id', $activeCommunityId)->count();
        $totalParticipants = \App\Models\EventParticipant::whereHas('event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $attendedCount = \App\Models\Attendance::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $attendanceRate = $totalParticipants > 0 ? round(($attendedCount / $totalParticipants) * 100, 1) : 0;
        
        $certificatesGenerated = \App\Models\Certificate::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();

        // Get upcoming events
        $upcomingEvents = \App\Models\Event::where('community_id', $activeCommunityId)
                            ->orderBy('start_date', 'desc')->take(3)->get();

        // Get recent activities related to the active community (or simply all for now, or filter if activity log has community context)
        // Since ActivityLog might not have community_id, let's just get the recent ones.
        $recentActivities = \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        $activeCommunity = \App\Models\Community::find($activeCommunityId);

        return view('Pages.Dashboard', compact(
            'totalEvents',
            'totalParticipants',
            'attendanceRate',
            'certificatesGenerated',
            'upcomingEvents',
            'recentActivities',
            'activeCommunity'
        ));
    }

    public function manage()
    {
        $activeCommunityId = session('active_community_id');
        $eventsList = \App\Models\Event::where('community_id', $activeCommunityId)
                        ->with('community')->withCount('participants')->get();
        return view('Pages.EventManagement', compact('eventsList'));
    }

    public function show($id)
    {
        $event = \App\Models\Event::with('participants.user')->findOrFail($id);
        
        $registeredCount = $event->participants->where('status', 'Registered')->count();
        $attendedCount = $event->participants->where('status', 'Attended')->count();
        $waitlistedCount = $event->participants->where('status', 'Waitlisted')->count();
        
        $totalForConversion = $registeredCount + $attendedCount;
        $conversionRate = $event->capacity > 0 ? round(($totalForConversion / $event->capacity) * 100, 1) : 0;

        return view('Pages.EventDetail', compact('event', 'registeredCount', 'attendedCount', 'waitlistedCount', 'conversionRate'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'community_id' => 'required|exists:communities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if (!auth()->user()->canManageEvent($validated['community_id'])) {
            abort(403, 'Unauthorized to create events for this community.');
        }

        $validated['status'] = 'Draft';

        Event::create($validated);

        return back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        if (!auth()->user()->canManageEvent($event->community_id)) {
            abort(403, 'Unauthorized to edit this event.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $event->update($validated);

        return back()->with('success', 'Event updated successfully.');
    }
}
