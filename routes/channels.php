<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\CommunityMember;
use App\Models\Event;
use App\Models\EventParticipant;

// Channel 1: Community Feed (Member Only)
Broadcast::channel('community.{id}.feed', function ($user, $id) {
    // Super admins have global access
    if (isset($user->roles) && in_array('Super Admin', $user->roles)) {
        return true;
    }

    // Check if user is a member of this community
    return CommunityMember::where('community_id', $id)
        ->where('user_id', $user->id)
        ->exists();
});

// Channel 2: Community Activities (Owner/Moderator Only)
Broadcast::channel('community.{id}.activities', function ($user, $id) {
    // Super admins have global access
    if (isset($user->roles) && in_array('Super Admin', $user->roles)) {
        return true;
    }

    $member = CommunityMember::where('community_id', $id)
        ->where('user_id', $user->id)
        ->first();
        
    return $member && in_array($member->role, ['owner', 'moderator']);
});

// Channel 3: Event Attendance (Owner/Moderator Only)
Broadcast::channel('event.{id}.attendance', function ($user, $id) {
    // Super admins have global access
    if (isset($user->roles) && in_array('Super Admin', $user->roles)) {
        return true;
    }

    $event = Event::find($id);
    if (!$event) return false;

    $member = CommunityMember::where('community_id', $event->community_id)
        ->where('user_id', $user->id)
        ->first();
        
    return $member && in_array($member->role, ['owner', 'moderator']);
});
