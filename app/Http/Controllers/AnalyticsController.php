<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $activeCommunityId = session('active_community_id');
        if (!$activeCommunityId) {
            abort(403, 'No active community selected.');
        }

        // Base query for events in the active community
        $communityEventsQuery = \App\Models\Event::where('community_id', $activeCommunityId);

        $totalParticipants = \App\Models\EventParticipant::whereHas('event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $totalAttendances = \App\Models\Attendance::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $avgAttendance = $totalParticipants > 0 ? ($totalAttendances / $totalParticipants) * 100 : 0;
        
        // Count finished events based on end_date (since getStatusAttribute returns 'Finished')
        $completedEvents = (clone $communityEventsQuery)->where('end_date', '<', now())->count();
        $totalEvents = (clone $communityEventsQuery)->count();
        $successRate = $totalEvents > 0 ? ($completedEvents / $totalEvents) * 100 : 0;

        $recentEvents = (clone $communityEventsQuery)->withCount('participants')->latest()->take(5)->get();

        // Certificates Data
        $issuedCertificates = \App\Models\Certificate::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        // Since we don't track "Pending" certificates explicitly in a table, we can estimate it as total participants minus issued.
        $pendingCertificates = max(0, $totalParticipants - $issuedCertificates);
        $certificateIssuedPercentage = $totalParticipants > 0 ? round(($issuedCertificates / $totalParticipants) * 100) : 0;

        // Participation Growth Data (Unique vs Returning) - Last 6 Months
        $growthLabels = [];
        $uniqueData = [];
        $returningData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $growthLabels[] = $month->format('M');
            
            // Get participants for events starting this month
            $participantsThisMonth = \App\Models\EventParticipant::whereHas('event', function($q) use ($activeCommunityId, $month) {
                $q->where('community_id', $activeCommunityId)
                  ->whereMonth('start_date', $month->month)
                  ->whereYear('start_date', $month->year);
            })->get();

            // Total participants this month
            $totalThisMonth = $participantsThisMonth->count();
            // Approximating returning (e.g. if the user_id appeared before this month)
            // For simplicity, let's just make unique = total, returning = 0 for now unless we do heavy querying.
            // Actually, let's query users who have participated in an earlier event.
            $returningCount = 0;
            foreach ($participantsThisMonth as $p) {
                if ($p->user_id) {
                    $pastParticipation = \App\Models\EventParticipant::where('user_id', $p->user_id)
                        ->where('id', '<', $p->id)
                        ->whereHas('event', function($q) use ($activeCommunityId) {
                            $q->where('community_id', $activeCommunityId);
                        })->exists();
                    if ($pastParticipation) {
                        $returningCount++;
                    }
                }
            }
            $uniqueCount = max(0, $totalThisMonth - $returningCount);

            $uniqueData[] = $uniqueCount;
            $returningData[] = $returningCount;
        }

        return view('Pages.Analytics', compact(
            'totalParticipants', 'avgAttendance', 'successRate', 'recentEvents',
            'issuedCertificates', 'pendingCertificates', 'certificateIssuedPercentage',
            'growthLabels', 'uniqueData', 'returningData'
        ));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,csv,excel',
            'data_points' => 'required|array',
        ]);

        // Logic to generate and download analytics report based on selected options
        // return Excel::download(new AnalyticsExport($validated), 'analytics.' . $validated['format']);

        return back()->with('success', 'Report export started.');
    }
}
