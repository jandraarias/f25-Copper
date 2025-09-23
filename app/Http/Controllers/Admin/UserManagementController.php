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

            // Only required when creating a traveler user:
            'date_of_birth' => [Rule::requiredIf(fn () => $request->role === 'traveler'), 'date'],
            'phone_number'  => [Rule::requiredIf(fn () => $request->role === 'traveler'), 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => $request->input('role'),
        ]);

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
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    // NEW: list users
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

    // NEW: edit form
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // NEW: update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', Rule::in(['traveler', 'expert', 'business', 'admin'])],
            'password' => ['nullable', 'confirmed', 'min:8'],

            // If setting/keeping traveler role, ensure these exist:
            'date_of_birth' => [Rule::requiredIf(fn () => $request->role === 'traveler'), 'nullable', 'date'],
            'phone_number'  => [Rule::requiredIf(fn () => $request->role === 'traveler'), 'nullable', 'string', 'max:20'],
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Ensure traveler profile exists/updated when role is traveler
        if ($user->role === 'traveler') {
            $traveler = Traveler::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'date_of_birth' => $request->input('date_of_birth'),
                    'phone_number'  => $request->input('phone_number'),
                    'bio'           => optional($user->traveler)->bio,
                ]
            );

            // If it already existed, update the fields that might have changed
            $traveler->update([
                'name'          => $user->name,
                'email'         => $user->email,
                'date_of_birth' => $request->input('date_of_birth'),
                'phone_number'  => $request->input('phone_number'),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    // NEW: delete user
    public function destroy(User $user)
    {
        /* Optional safety: prevent self-deletion
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        } */

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
