<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\CommunityMessage;
use App\Services\UserService;
use App\Traits\CommunityAuthorization;

class CommunityController extends Controller
{
    use CommunityAuthorization;

    public function index(Request $request)
    {
        $userId = $request->auth_user_id;
        $globalRoles = $request->auth_user_roles ?? [];

        if (in_array('Super Admin', $globalRoles)) {
            // Super Admin sees all communities
            $communities = Community::with('members')->paginate(15);
        } else {
            // Get user communities where they are a member
            $communities = Community::whereHas('members', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->with('members')->paginate(15);
        }

        // Data Stitching: Fetch all owners
        $ownerIds = $communities->pluck('owner_id')->unique()->toArray();
        $usersData = UserService::getUsersBatch($ownerIds);

        $communities->getCollection()->transform(function ($community) use ($usersData) {
            $community->owner_detail = $usersData[$community->owner_id] ?? null;
            return $community;
        });

        return response()->json([
            'success' => true,
            'data' => $communities
        ], 200);
    }

    public function myMemberships(Request $request)
    {
        $userId = $request->auth_user_id;
        $memberships = \App\Models\CommunityMember::where('user_id', $userId)
            ->with('community')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $memberships
        ], 200);
    }

    public function store(Request $request)
    {
        $userId = $request->auth_user_id;
        $globalRoles = $request->auth_user_roles ?? [];

        // Check if user is already an Owner of any community
        $isAlreadyOwner = \App\Models\CommunityMember::where('user_id', $userId)
            ->where('role', 'Owner')
            ->exists();

        // Super Admin can bypass this limit
        if ($isAlreadyOwner && !in_array('Super Admin', $globalRoles)) {
            return response()->json(['success' => false, 'message' => 'Anda hanya diizinkan untuk membuat/memiliki 1 komunitas.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['owner_id'] = $request->auth_user_id;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $community = Community::create($validated);

            $community->members()->create([
                'user_id' => $validated['owner_id'],
                'role' => 'Owner'
            ]);
            
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create community', 'error' => $e->getMessage()], 500);
        }

        // Stitching
        $userData = UserService::getUser($validated['owner_id']);
        $community->owner_detail = $userData;

        return response()->json([
            'success' => true,
            'message' => 'Community created successfully', 
            'data' => $community
        ], 201);
    }

    public function feed($id)
    {
        $community = Community::findOrFail($id);
        
        $messages = CommunityMessage::where('community_id', $id)
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get();
            
        // Fetch user details for the messages
        $userIds = $messages->pluck('user_id')->unique()->toArray();
        $usersData = UserService::getUsersBatch($userIds);
        
        // Also fetch user roles in this community
        $memberships = \App\Models\CommunityMember::where('community_id', $id)
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $messages->transform(function ($message) use ($usersData, $memberships) {
            $message->user_detail = collect($usersData[$message->user_id] ?? [])->only(['id', 'name', 'email', 'roles']);
            $message->role = $memberships[$message->user_id]->role ?? 'Member';
            return $message;
        });

        return response()->json(['success' => true, 'data' => $messages], 200);
    }

    public function storeFeed(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);
        $community = Community::findOrFail($id);
        
        $message = CommunityMessage::create([
            'community_id' => $id,
            'user_id' => $request->auth_user_id,
            'content' => $request->content
        ]);
        
        // Fetch user details to broadcast with the message
        $usersData = UserService::getUsersBatch([$request->auth_user_id]);
        $membership = \App\Models\CommunityMember::where('community_id', $id)
            ->where('user_id', $request->auth_user_id)
            ->first();
            
        $message->user_detail = collect($usersData[$request->auth_user_id] ?? [])->only(['id', 'name', 'email', 'roles']);
        $message->role = $membership->role ?? 'Member';
        
        broadcast(new \App\Events\NewCommunityFeed($id, $message->toArray()));
        
        return response()->json(['success' => true, 'message' => 'Message posted successfully', 'data' => $message], 201);
    }

    public function updateRoles(Request $request, $id)
    {
        $this->authorizeCommunityAccess($id, ['Owner']);
        
        $community = Community::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Roles updated successfully'], 200);
    }

    public function members($id)
    {
        $community = Community::with('members')->findOrFail($id);
        
        $userIds = $community->members->pluck('user_id')->unique()->toArray();
        $usersData = UserService::getUsersBatch($userIds);

        $community->members->transform(function ($member) use ($usersData) {
            $member->user_detail = $usersData[$member->user_id] ?? null;
            return $member;
        });

        return response()->json([
            'success' => true,
            'data' => $community->members
        ], 200);
    }
    public function getMemberships(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        $memberships = \App\Models\CommunityMember::whereIn('user_id', $userIds)
            ->with('community')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $memberships
        ], 200);
    }

    public function updateUserRole(Request $request, $communityId, $userId)
    {
        $role = $request->input('role');
        
        if ($role === 'Not a Member') {
            \App\Models\CommunityMember::where('community_id', $communityId)
                ->where('user_id', $userId)
                ->delete();
        } else {
            \App\Models\CommunityMember::updateOrCreate(
                ['community_id' => $communityId, 'user_id' => $userId],
                ['role' => $role]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'User community role updated successfully'
        ], 200);
    }

    public function getAll(Request $request)
    {
        $communities = Community::all();
        return response()->json([
            'success' => true,
            'data' => $communities
        ], 200);
    }
}
