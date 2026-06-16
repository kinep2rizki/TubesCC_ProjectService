<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\CommunityMember;
use App\Models\Event;
use App\Models\EventParticipant;

// Channel 1: Community Feed (Member Only)
Broadcast::channel('community.{id}.feed', function ($user, $id) {
    // Check if user is a member of this community
    return CommunityMember::where('community_id', $id)
        ->where('user_id', $user->id)
        ->exists();
});

// Channel 2: Community Activities (Owner/Moderator Only)
Broadcast::channel('community.{id}.activities', function ($user, $id) {
    $member = CommunityMember::where('community_id', $id)
        ->where('user_id', $user->id)
        ->first();
        
    return $member && in_array($member->role, ['Owner', 'Moderator']);
});

// Channel 3: Event Attendance (Owner/Moderator Only)
Broadcast::channel('event.{id}.attendance', function ($user, $id) {
    $event = Event::find($id);
    if (!$event) return false;

    $member = CommunityMember::where('community_id', $event->community_id)
        ->where('user_id', $user->id)
        ->first();

    return $member && in_array($member->role, ['Owner', 'Moderator']);
});
