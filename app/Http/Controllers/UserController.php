<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Community;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $activeCommunityId = session('active_community_id');
        $isOwner = false;

        if ($isSuperAdmin) {
            $communities = Community::all();
            $communityId = $request->query('community_id', 'all');
        } else {
            if (!$activeCommunityId) {
                abort(403, 'Please select a community from the top menu first.');
            }
            
            // Validate they are Admin or Owner in the active community
            $membership = \App\Models\CommunityMember::where('user_id', $user->id)
                            ->where('community_id', $activeCommunityId)
                            ->whereIn('role', ['Admin', 'Owner'])
                            ->first();
                            
            if (!$membership) {
                abort(403, 'Unauthorized to manage users in this community. Please switch to a community where you are an Admin or Owner.');
            }

            if ($membership->role === 'Owner') {
                $isOwner = true;
            }

            $communities = collect([$membership->community]);
            $communityId = $activeCommunityId;
        }

        // Query Users
        $query = User::with(['communityMemberships.community', 'roles']);

        if ($communityId !== 'all') {
            $query->whereHas('communityMemberships', function($q) use ($communityId) {
                $q->where('community_id', $communityId);
            });
        }

        // Search feature
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('Pages.Users', compact('users', 'communities', 'communityId', 'isSuperAdmin', 'isOwner'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        
        // Find if user is owner of the active community
        $activeCommunityId = session('active_community_id');
        $isOwner = false;
        
        if (!$isSuperAdmin && $activeCommunityId) {
            $isOwner = \App\Models\CommunityMember::where('user_id', $user->id)
                ->where('community_id', $activeCommunityId)
                ->where('role', 'Owner')
                ->exists();
        }

        if (!$isSuperAdmin && !$isOwner) {
            abort(403, 'Only Super Admin or Community Owner can update roles.');
        }

        // Validate request depending on role
        if ($isSuperAdmin) {
            $validated = $request->validate([
                'role' => 'required|string|in:User,Super Admin',
                'communities' => 'nullable|array',
                'communities.*' => 'string|in:Not a Member,Member,Moderator,Admin,Owner'
            ]);
        } else {
            // Owner can only update community role for their active community
            $validated = $request->validate([
                'communities' => 'nullable|array',
                'communities.' . $activeCommunityId => 'string|in:Not a Member,Member,Moderator,Admin,Owner'
            ]);
        }

        $targetUser = User::findOrFail($id);
        
        // Sync global roles only if Super Admin
        if ($isSuperAdmin && isset($validated['role'])) {
            if ($validated['role'] === 'User') {
                $targetUser->syncRoles([]);
            } else {
                $targetUser->syncRoles([$validated['role']]);
            }
        }

        // Sync community memberships
        if (isset($validated['communities'])) {
            foreach ($validated['communities'] as $communityId => $role) {
                // If not super admin, they can ONLY modify the community they own
                if (!$isSuperAdmin && $communityId != $activeCommunityId) {
                    continue; // Skip silently or we could abort
                }

                if ($role === 'Not a Member') {
                    \App\Models\CommunityMember::where('user_id', $targetUser->id)
                        ->where('community_id', $communityId)
                        ->delete();
                } else {
                    \App\Models\CommunityMember::updateOrCreate(
                        ['user_id' => $targetUser->id, 'community_id' => $communityId],
                        ['role' => $role]
                    );
                }
            }
        }

        return back()->with('success', 'User role and communities updated successfully.');
    }
}
