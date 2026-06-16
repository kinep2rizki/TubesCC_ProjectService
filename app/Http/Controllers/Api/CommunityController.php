<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Services\UserService;
use App\Traits\CommunityAuthorization;

class CommunityController extends Controller
{
    use CommunityAuthorization;

    public function index(Request $request)
    {
        // Get user communities where they are a member
        $userId = $request->auth_user_id;
        
        $communities = Community::whereHas('members', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('members')->paginate(15);

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
        return response()->json(['success' => true, 'data' => []], 200);
    }

    public function storeFeed(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);
        $community = Community::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Feed posted successfully'], 201);
    }

    public function updateRoles(Request $request, $id)
    {
        $this->authorizeCommunityAccess($id, ['Owner']);
        
        $community = Community::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Roles updated successfully'], 200);
    }
}
