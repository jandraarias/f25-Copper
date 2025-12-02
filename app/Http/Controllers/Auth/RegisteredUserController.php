<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Traveler;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Base rules
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:traveler,expert,business,admin'],
        ];

        // Role-specific rules
        if (in_array($request->role, ['traveler', 'expert'])) {
            $rules['date_of_birth'] = ['required', 'date'];
            $rules['phone_number']  = ['required', 'string', 'max:20'];
        } elseif ($request->role === 'business') {
            $rules['phone_number'] = ['required', 'string', 'max:20'];
        }

        $validated = $request->validate($rules);

        // Prevent non-admins from creating admin users
        if (
            $validated['role'] === User::ROLE_ADMIN &&
            (!Auth::check() || Auth::user()->role !== User::ROLE_ADMIN)
        ) {
            abort(403, 'Unauthorized action.');
        }

        // Create base user
        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'role'         => $validated['role'],
            'date_of_birth'=> $validated['date_of_birth'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
        ]);

        /**
         * ==========================================================
         *  CREATE ROLE-SPECIFIC PROFILE TABLE ENTRIES
         * ==========================================================
         */

        // Traveler profile
        if ($user->role === User::ROLE_TRAVELER) {
            Traveler::create([
                'user_id' => $user->id,
                'bio'     => null,
            ]);
        }

        // Expert profile
        if ($user->role === User::ROLE_EXPERT) {
            \App\Models\Expert::create([
                'user_id'   => $user->id,
                'name'    => $user->name,
                'city'      => '',   // empty defaults until expert edits profile
                'photo_url' => null,
                'bio'       => null,
            ]);
        }

        // Business / Admin have no profile rows unless you want one

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
