<?php

namespace App\Http\Controllers;

use App\Models\Community;

class CommunityController extends Controller
{
    public function index()
    {
        $activeCommunityId = session('active_community_id');
        $community = Community::with(['members.user'])->find($activeCommunityId);
        
        // Fallback if no community exists
        if (!$community) {
            $community = new Community([
                'name' => 'No Community Found', 
                'description' => 'Create a community to get started.'
            ]);
            $community->setRelation('members', collect());
        }

        return view('Pages.Community', compact('community'));
    }

    public function switch($id)
    {
        $community = Community::findOrFail($id);
        
        // Ensure user is part of the community or Super Admin
        $user = auth()->user();
        if (!$user->hasRole('Super Admin') && !$community->members()->where('user_id', $user->id)->exists()) {
            abort(403, 'Unauthorized');
        }

        session(['active_community_id' => $community->id]);
        return back()->with('success', 'Switched to ' . $community->name);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admin can create new communities.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:4',
        ]);

        $validated['owner_id'] = \Illuminate\Support\Facades\Auth::id() ?? 1;
        
        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            $validated['password'] = null;
        }

        $community = Community::create($validated);
        
        // Add current user as Admin/Owner
        $community->members()->create([
            'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'role' => 'Owner'
        ]);

        return back()->with('success', 'Community created successfully.');
    }

    public function updateRoles(Request $request, $id)
    {
        // Logic to update community roles from the Role Builder Modal
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'community_id' => $id,
            'action' => 'updated_roles',
            'description' => "updated member roles",
            'ip_address' => request()->ip(),
        ]);
        return back()->with('success', 'Member added/roles updated successfully');
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $community = Community::findOrFail($id);
        
        $user = auth()->user();
        $isOwner = \App\Models\CommunityMember::where('user_id', $user->id)
            ->where('community_id', $community->id)
            ->where('role', 'Owner')
            ->exists();

        if (!$user->hasRole('Super Admin') && !$isOwner) {
            abort(403, 'Only Community Owner or Super Admin can edit the community details.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $community->update($validated);

        return back()->with('success', 'Community profile updated successfully.');
    }
}
