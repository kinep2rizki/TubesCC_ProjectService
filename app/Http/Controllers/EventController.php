<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        // This acts as the main dashboard overview
        $events = Event::with('community')->get();
        return view('Pages.Dashboard', compact('events'));
    }

    public function show($id)
    {
        $event = Event::with('participants')->findOrFail($id);
        return view('Pages.EventDetail', compact('event'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
        ]);

        // Logic to create event
        // Event::create($validated);

        return back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Logic to update event
        return back()->with('success', 'Event updated successfully.');
    }
}
