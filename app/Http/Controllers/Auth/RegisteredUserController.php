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

        $request->validate($rules);

        // Prevent non-admins from registering as admin
        if (
            $request->role === 'admin' &&
            (!Auth::check() || Auth::user()->role !== User::ROLE_ADMIN)
        ) {
            abort(403, 'Unauthorized action.');
        }

        // Build user data
        $userData = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ];

        // Add conditional fields
        if (in_array($request->role, ['traveler', 'expert'])) {
            $userData['date_of_birth'] = $request->date_of_birth;
            $userData['phone_number']  = $request->phone_number;
        } elseif ($request->role === 'business') {
            $userData['phone_number'] = $request->phone_number;
        }

        // Create the user
        $user = User::create($userData);

        // Traveler profile
        if ($user->role === User::ROLE_TRAVELER) {
            Traveler::create([
                'user_id' => $user->id,
                'bio'     => null,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
