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

        $chartData = [
            'monthlyEvents' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [12, 19, 3, 5, 2, 3]
            ],
            'attendanceTrends' => [
                'labels' => ['Event A', 'Event B', 'Event C'],
                'data' => [85, 90, 78]
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
        
        // Logic to generate and return report
        return response()->json(['success' => true, 'message' => 'Export initiated'], 200);
    }
}
