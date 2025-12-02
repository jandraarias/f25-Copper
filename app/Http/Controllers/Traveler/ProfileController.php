<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the traveler profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();

        // Ensure traveler profile exists
        if (!$user->traveler) {
            $user->traveler()->create([
                'bio' => '',
            ]);
        }

        $traveler = $user->traveler;

        return view('traveler.profile.edit', compact('traveler', 'user'));
    }

    /**
     * Update the traveler's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $traveler = $user->traveler;

        // Ensure traveler profile exists
        if (!$traveler) {
            $traveler = $user->traveler()->create([]);
        }

        $data = $request->validate([
            'bio'   => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle traveler photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('traveler_photos', 'public');
            $traveler->profile_photo_path = $path;
        }

        // Update traveler bio
        if (array_key_exists('bio', $data)) {
            $traveler->bio = $data['bio'];
        }

        $traveler->save();

        return redirect()
            ->route('traveler.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the traveler profile.
     */
    public function show()
    {
        $user = Auth::user();

        // Ensure traveler profile exists
        if (!$user->traveler) {
            $user->traveler()->create(['bio' => '']);
        }

        $traveler = $user->traveler;

        return view('traveler.profile.show', compact('traveler'));
    }
}
