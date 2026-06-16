<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CommunityAuthorization;

class AnalyticsController extends Controller
{
    use CommunityAuthorization;

    public function dashboard($communityId)
    {
        $this->authorizeCommunityAccess($communityId, ['Owner']);

        $communityEvents = \App\Models\Event::where('community_id', $communityId);
        $eventIds = $communityEvents->pluck('id')->toArray();

        // 1. Total Events
        $totalEvents = $communityEvents->count();

        // 2. Total Participants (across all events in community)
        $totalParticipants = \App\Models\EventParticipant::whereIn('event_id', $eventIds)->count();

        // 3. Attendance Rate
        $totalAttended = \App\Models\Attendance::whereHas('participant', function($q) use ($eventIds) {
            $q->whereIn('event_id', $eventIds);
        })->count();
        $attendanceRate = $totalParticipants > 0 ? round(($totalAttended / $totalParticipants) * 100, 2) : 0;

        // 4. Certificates Generated
        // Assuming a Certificate model exists that links to participant or event
        // If it doesn't link directly, we can count certificates for the participants of these events
        $certificatesGenerated = \Illuminate\Support\Facades\DB::table('certificates')
            ->join('event_participants', 'certificates.event_participant_id', '=', 'event_participants.id')
            ->whereIn('event_participants.event_id', $eventIds)
            ->count();

        // 5. Upcoming Events
        $upcomingEvents = \App\Models\Event::where('community_id', $communityId)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // 6. Recent Activities
        $recentActivities = \App\Models\ActivityLog::where('community_id', $communityId)
            ->latest()
            ->take(5)
            ->get();

        // 7. Data Pertumbuhan Event Bulanan (Real Data)
        $events = \App\Models\Event::where('community_id', $communityId)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('M'); // 'Jan', 'Feb', dll
            });
            
        $monthlyLabels = [];
        $monthlyData = [];
        foreach($events as $month => $evts) {
            $monthlyLabels[] = $month;
            $monthlyData[] = count($evts);
        }

        // 8. Data Tren Kehadiran di 5 Event Terakhir (Real Data)
        $latestEvents = \App\Models\Event::where('community_id', $communityId)
            ->latest()
            ->take(5)
            ->get();
            
        $attendanceLabels = [];
        $attendanceData = [];
        
        // Reverse agar urutannya dari event terlama ke terbaru di grafik
        foreach($latestEvents->reverse() as $evt) {
            $attendanceLabels[] = $evt->title;
            
            $presentCount = \App\Models\Attendance::whereHas('participant', function($q) use ($evt) {
                $q->where('event_id', $evt->id);
            })->count();
            
            $attendanceData[] = $presentCount;
        }

        $chartData = [
            'metrics' => [
                'totalEvents' => $totalEvents,
                'totalParticipants' => $totalParticipants,
                'attendanceRate' => $attendanceRate,
                'certificatesGenerated' => $certificatesGenerated
            ],
            'upcomingEvents' => $upcomingEvents,
            'recentActivities' => $recentActivities,
            'monthlyEvents' => [
                'labels' => empty($monthlyLabels) ? ['No Data'] : $monthlyLabels,
                'data' => empty($monthlyData) ? [0] : $monthlyData
            ],
            'attendanceTrends' => [
                'labels' => empty($attendanceLabels) ? ['No Data'] : $attendanceLabels,
                'data' => empty($attendanceData) ? [0] : $attendanceData
            ]
        ];

        return response()->json(['success' => true, 'data' => $chartData], 200);
    }

    public function export(Request $request, $communityId)
    {
        $this->authorizeCommunityAccess($communityId, ['Owner']);

        $validated = $request->validate([
            'format' => 'required|in:pdf,csv',
            'include_metrics' => 'nullable|boolean'
        ]);
        
        $format = $request->input('format', 'csv');
        $community = \App\Models\Community::find($communityId);
        $events = \App\Models\Event::where('community_id', $communityId)
            ->withCount('participants')
            ->withCount(['participants as attended_count' => function ($query) {
                $query->where('status', 'Attended');
            }])
            ->get();

        $data = [];
        foreach ($events as $event) {
            $attendanceRate = $event->participants_count > 0 ? round(($event->attended_count / $event->participants_count) * 100) : 0;
            $data[] = [
                'Event Title' => $event->title,
                'Start Date' => \Carbon\Carbon::parse($event->start_date)->format('Y-m-d H:i'),
                'Total Participants' => $event->participants_count,
                'Attended' => $event->attended_count,
                'Attendance Rate (%)' => $attendanceRate,
                'Status' => $event->status
            ];
        }

        $filename = 'analytics_report_' . date('Y-m-d');

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('analytics.export', compact('data', 'community'));
            return $pdf->download($filename . '.pdf');
        } else {
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($data) {
                $file = fopen('php://output', 'w');
                if (count($data) > 0) {
                    fputcsv($file, array_keys($data[0]));
                    foreach ($data as $row) {
                        fputcsv($file, $row);
                    }
                } else {
                    fputcsv($file, ['No data available']);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }
}
