<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role'     => ['required', Rule::in(['traveler', 'expert', 'business', 'admin'])],

            // Phone: required for all except admin
            'phone_number'  => ['nullable', 'required_unless:role,admin', 'string', 'max:20'],
            // DOB: required for all except business and admin
            'date_of_birth' => ['nullable', 'required_unless:role,business,admin', 'date'],
        ]);

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => $request->input('role'),
        ]);

        // Persist on users table for ALL roles (respecting your policy)
        $user->phone_number  = $request->input('phone_number');  // may be null for admin
        $user->date_of_birth = $request->input('date_of_birth'); // may be null for business/admin
        $user->save();

        // Also ensure Traveler profile exists/updated if role = traveler
        if ($user->role === 'traveler') {
            Traveler::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'date_of_birth' => $request->input('date_of_birth'),
                    'phone_number'  => $request->input('phone_number'),
                    'bio'           => null,
                ]
            )->update([
                'name'          => $user->name,
                'email'         => $user->email,
                'date_of_birth' => $request->input('date_of_birth'),
                'phone_number'  => $request->input('phone_number'),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function index(Request $request)
    {
        $q    = (string) $request->query('q', '');
        $role = (string) $request->query('role', '');

        $users = User::query()
            ->when($q !== '', function ($qry) use ($q) {
                $qry->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($role !== '', fn ($qry) => $qry->where('role', $role))
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', Rule::in(['traveler', 'expert', 'business', 'admin'])],
            'password' => ['nullable', 'confirmed', 'min:8'],

            // Phone: required for all except admin
            'phone_number'  => ['nullable', 'required_unless:role,admin', 'string', 'max:20'],
            // DOB: required for all except business and admin
            'date_of_birth' => ['nullable', 'required_unless:role,business,admin', 'date'],
        ]);

        $user->name  = $request->input('name');
        $user->email = $request->input('email');
        $user->role  = $request->input('role');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Persist on users table for ALL roles
        $user->phone_number  = $request->input('phone_number');  // null for admin
        $user->date_of_birth = $request->input('date_of_birth'); // null for business/admin
        $user->save();

        // Keep Traveler profile synced when role = traveler
        if ($user->role === 'traveler') {
            Traveler::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'date_of_birth' => $request->input('date_of_birth'),
                    'phone_number'  => $request->input('phone_number'),
                    'bio'           => optional($user->traveler)->bio,
                ]
            )->update([
                'name'          => $user->name,
                'email'         => $user->email,
                'date_of_birth' => $request->input('date_of_birth'),
                'phone_number'  => $request->input('phone_number'),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        /* Avoid self-deletion
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        */
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
