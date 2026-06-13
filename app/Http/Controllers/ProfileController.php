<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('Pages.PlatformSettings');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // 'avatar' => 'nullable|image|max:2048'
        ]);

        // Logic to update user profile
        // auth()->user()->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateSecurity(Request $request)
    {
        // Logic to update password
        return back()->with('success', 'Security settings updated.');
    }

    public function updateNotifications(Request $request)
    {
        // Logic to update notification preferences
        return back()->with('success', 'Notification preferences saved.');
    }
}
