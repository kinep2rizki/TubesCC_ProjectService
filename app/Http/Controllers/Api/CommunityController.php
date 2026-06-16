<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Services\UserService;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        // Get user communities where they are a member
        $userId = $request->auth_user_id;
        
        $communities = Community::whereHas('members', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('members')->get();

        // Data Stitching: Fetch all owners
        $ownerIds = $communities->pluck('owner_id')->unique()->toArray();
        $usersData = UserService::getUsersBatch($ownerIds);

        $communities->transform(function ($community) use ($usersData) {
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['owner_id'] = $request->auth_user_id;

        $community = Community::create($validated);

        $community->members()->create([
            'user_id' => $validated['owner_id'],
            'role' => 'Owner'
        ]);

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
        $community = Community::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Roles updated successfully'], 200);
    }
}
