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
        $query = Event::with('community')
                    ->withCount('participants')
                    ->withCount(['participants as attended_count' => function ($query) {
                        $query->where('status', 'Attended');
                    }]);
        
        if ($request->has('community_id')) {
            $query->where('community_id', $request->community_id);
        }

        $events = $query->latest()->paginate(15);
        
        // Calculate attendance rate for each event
        $events->getCollection()->transform(function ($event) {
            $event->attendance_rate = $event->participants_count > 0 
                ? round(($event->attended_count / $event->participants_count) * 100) 
                : 0;
            return $event;
        });

        return response()->json(['success' => true, 'data' => $events], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'community_id' => 'required|exists:communities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->authorizeCommunityAccess($validated['community_id'], ['Owner', 'Admin', 'Moderator']);

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
        
        // 1. Calculate Registration Stats
        $registeredCount = $event->participants()->where('status', 'Registered')->count();
        $attendedCount = $event->participants()->where('status', 'Attended')->count();
        $waitlistedCount = $event->participants()->where('status', 'Waitlisted')->count();
        $notAttendingCount = $event->participants()->where('status', 'Not Attending')->count();
        
        $totalForConversion = $registeredCount + $attendedCount;
        $conversionRate = $event->capacity > 0 ? round(($totalForConversion / $event->capacity) * 100, 1) : 0;

        // 2. Calculate Demographics
        $totalParticipants = $event->participants()->count();
        $attendedPct = $totalParticipants > 0 ? round(($attendedCount / $totalParticipants) * 100) : 0;
        $registeredPct = $totalParticipants > 0 ? round(($registeredCount / $totalParticipants) * 100) : 0;
        $otherPct = 100 - $attendedPct - $registeredPct;
        if ($totalParticipants == 0) $otherPct = 0;
        
        $topGroupPct = max($attendedPct, $registeredPct, $otherPct);
        $topGroupName = $topGroupPct == $attendedPct ? 'Attended' : ($topGroupPct == $registeredPct ? 'Registered' : 'Other');

        // 3. Registration growth over last 7 days (Chart Data)
        $registrationDates = [];
        $registrationCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $count = $event->participants()->whereDate('created_at', '<=', $date)->count();
            $registrationDates[] = \Carbon\Carbon::now()->subDays($i)->format('M d');
            $registrationCounts[] = $count;
        }

        // 4. Fetch recent participants (we need data stitching if users are in Auth Service)
        // Note: ProjectService has UserService to fetch user details.
        $recentParticipants = collect($event->participants()->latest()->take(5)->get());
        
        $userIds = $recentParticipants->pluck('user_id')->unique()->toArray();
        if (!empty($userIds)) {
            $userService = new UserService();
            $usersMap = $userService->getUsersBatch($userIds);

            $recentParticipants->transform(function ($participant) use ($usersMap) {
                $participant->user = $usersMap[$participant->user_id] ?? ['name' => 'Unknown User', 'email' => 'N/A'];
                return $participant;
            });
        }

        // Bundle everything into the response
        $event->stats = [
            'participants' => $totalParticipants,
            'registered' => $registeredCount,
            'attended' => $attendedCount,
            'waitlisted' => $waitlistedCount,
            'notAttending' => $notAttendingCount,
            'conversionRate' => $conversionRate
        ];
        
        $event->demographics = [
            'attendedPct' => $attendedPct,
            'registeredPct' => $registeredPct,
            'otherPct' => $otherPct,
            'topGroupPct' => $topGroupPct,
            'topGroupName' => $topGroupName
        ];
        
        $event->registrationChart = [
            'labels' => $registrationDates,
            'data' => $registrationCounts
        ];
        
        $event->recentParticipants = $recentParticipants;

        return response()->json(['success' => true, 'data' => $event], 200);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Admin', 'Moderator']);

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
        
        $this->authorizeCommunityAccess($event->community_id, ['Owner', 'Admin', 'Moderator']);

        $event->delete();
        
        return response()->json(['success' => true, 'message' => 'Event deleted successfully'], 200);
    }
}
