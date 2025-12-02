<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user'     => $request->user(),
            'traveler' => $request->user()->traveler,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'bio'   => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'max:2048'], // <--- NEW
        ];

        // Shared roles (traveler + expert + business)
        if (in_array($user->role, ['traveler', 'expert', 'business'])) {
            $rules['phone_number'] = ['required', 'string', 'max:20'];
        }

        $validated = $request->validate($rules);

        // Update User fields
        $updatableUserFields = collect($validated)->except(['bio', 'photo'])->toArray();
        $user->fill($updatableUserFields);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // === Handle Traveler Profile changes ===
        if ($user->isTraveler()) {
            $traveler = $user->traveler;

            if (!$traveler) {
                $traveler = $user->traveler()->create([]);
            }

            // Update Traveler fields
            if (array_key_exists('bio', $validated)) {
                $traveler->bio = $validated['bio'];
            }

            // Handle Traveler photo upload
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('traveler_photos', 'public');
                $traveler->profile_photo_path = $path;
            }

            $traveler->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
