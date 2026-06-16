<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ActivityLog;
use App\Services\UserService;
use App\Traits\CommunityAuthorization;

class EventController extends Controller
{
    use CommunityAuthorization;

    public function index(Request $request)
    {
        $query = Event::with('community');
        
        if ($request->has('community_id')) {
            $query->where('community_id', $request->community_id);
        }

        $events = $query->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $events], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'community_id' => 'required|exists:communities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->authorizeCommunityAccess($validated['community_id'], ['Owner']);

        $validated['status'] = 'Ongoing';

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $event = Event::create($validated);

            ActivityLog::create([
                'user_id' => $request->auth_user_id,
                'community_id' => $validated['community_id'],
                'action' => 'created_event',
                'description' => "created a new event '{$event->title}'",
                'ip_address' => request()->ip(),
            ]);

            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create event', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Event created successfully', 
            'data' => $event
        ], 201);
    }

    public function show($id)
    {
        $event = Event::with(['community'])->findOrFail($id);
        
        // Return event along with stats
        $registeredCount = $event->participants()->where('status', 'Registered')->count();
        $attendedCount = $event->participants()->where('status', 'Attended')->count();

        $event->stats = [
            'registered' => $registeredCount,
            'attended' => $attendedCount
        ];

        return response()->json(['success' => true, 'data' => $event], 200);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $this->authorizeCommunityAccess($event->community_id, ['Owner']);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'location' => 'string|max:255',
            'status' => 'string',
            'description' => 'nullable|string',
        ]);

        $event->update($validated);

        return response()->json([
            'success' => true, 
            'message' => 'Event updated successfully', 
            'data' => $event
        ], 200);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        
        $this->authorizeCommunityAccess($event->community_id, ['Owner']);

        $event->delete();
        
        return response()->json(['success' => true, 'message' => 'Event deleted successfully'], 200);
    }
}
