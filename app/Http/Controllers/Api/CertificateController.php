<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\EventParticipant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Services\UserService;

class CertificateController extends Controller
{
    public function templates()
    {
        return response()->json(['success' => true, 'data' => []], 200);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'template_style' => 'required|string',
            'participant_ids' => 'nullable|array'
        ]);
        
        $eventId = $request->event_id;
        $templateStyle = $request->template_style;
        $participantIds = $request->participant_ids;

        $event = Event::findOrFail($eventId);

        $query = EventParticipant::where('event_id', $eventId)
            ->where('status', 'Attended');

        if (!empty($participantIds)) {
            $query->whereIn('id', $participantIds);
        }

        $participants = $query->get();

        if ($participants->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No attended participants found for generating certificates.'], 404);
        }

        // Data Stitching for Name
        $userIds = $participants->pluck('user_id')->unique()->toArray();
        $usersData = UserService::getUsersBatch($userIds);

        $generatedCount = 0;

        foreach ($participants as $participant) {
            $userName = $usersData[$participant->user_id]['name'] ?? 'Participant';

            $data = [
                'participantName' => $userName,
                'eventName' => $event->title,
                'eventDate' => \Carbon\Carbon::parse($event->start_date)->format('M d, Y'),
                'template_style' => $templateStyle
            ];

            // If the view doesn't exist, it will throw an error, but we assume it's there
            $pdf = Pdf::loadView('certificates.template', $data)
                ->setPaper('a4', 'landscape');

            $fileName = 'certificates/event_' . $eventId . '/cert_' . $participant->id . '.pdf';
            
            Storage::disk('public')->put($fileName, $pdf->output());

            Certificate::updateOrCreate(
                ['event_participant_id' => $participant->id],
                [
                    'template_style' => $templateStyle,
                    'file_url' => url('storage/' . $fileName),
                    'issued_at' => now()
                ]
            );

            $generatedCount++;
        }

        return response()->json(['success' => true, 'message' => "Successfully generated $generatedCount certificates."], 200);
    }

    public function download($id)
    {
        $certificate = Certificate::findOrFail($id);
        return response()->json(['success' => true, 'data' => ['url' => $certificate->file_url]], 200);
    }
}
