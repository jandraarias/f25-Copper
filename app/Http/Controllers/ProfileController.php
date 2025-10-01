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
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Base validation
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'bio'   => ['nullable', 'string', 'max:500'],
        ];

        // Role-specific validation
        if (in_array($user->role, ['traveler', 'expert'])) {
            $rules['phone_number']  = ['required', 'string', 'max:20'];
            // still validate date_of_birth so form errors work, but we won’t overwrite it
            $rules['date_of_birth'] = ['required', 'date'];
        } elseif ($user->role === 'business') {
            $rules['phone_number'] = ['required', 'string', 'max:20'];
        }

        $validated = $request->validate($rules);

        // Update user fields (except date_of_birth, which is view-only)
        $updatable = collect($validated)->except('date_of_birth')->toArray();
        $user->fill($updatable);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update Traveler bio if present
        if (array_key_exists('bio', $validated)) {
            $traveler = $user->traveler;
            if ($traveler) {
                $traveler->bio = $validated['bio'];
                $traveler->save();
            }
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
