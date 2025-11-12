<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
      
        $profile = auth()->user()->profile ?? auth()->user()->profile()->create([]);

      
        if ($profile->avatar) {
            $profile->avatar = asset('storage/' . $profile->avatar);
        }

        return response()->json($profile);
    }

    /**
     *
     */
    public function update(Request $request)
    {
        $profile = auth()->user()->profile ?? auth()->user()->profile()->create([]);

        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['bio', 'phone', 'address']);

        
        if ($request->hasFile('avatar')) {
            if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                Storage::disk('public')->delete($profile->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $profile->update($data);

        // Add full URL for avatar
        if ($profile->avatar) {
            $profile->avatar = asset('storage/' . $profile->avatar);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile
        ]);
    }
}
