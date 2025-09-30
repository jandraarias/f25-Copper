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
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'confirmed', 'min:8'],
            'role'          => ['required', Rule::in(['traveler', 'expert', 'business', 'admin'])],
            'phone_number'  => ['nullable', 'required_unless:role,admin', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'required_unless:role,business,admin', 'date'],
        ]);

        $user = User::create([
            'name'          => $request->input('name'),
            'email'         => $request->input('email'),
            'password'      => Hash::make($request->input('password')),
            'role'          => $request->input('role'),
            'phone_number'  => $request->input('phone_number'),
            'date_of_birth' => $request->input('date_of_birth'),
        ]);

        // Create a Traveler shell only for traveler role (no PII duplication)
        if ($user->role === 'traveler') {
            $traveler = Traveler::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'bio'  => null,
                ]
            );

            // Keep traveler name in sync with user name
            if ($traveler->name !== $user->name) {
                $traveler->update(['name' => $user->name]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function index(Request $request)
    {
        $users = $this->filteredUsersQuery($request)
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
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'          => ['required', Rule::in(['traveler', 'expert', 'business', 'admin'])],
            'password'      => ['nullable', 'confirmed', 'min:8'],
            'phone_number'  => ['nullable', 'required_unless:role,admin', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'required_unless:role,business,admin', 'date'],
        ]);

        $user->name  = $request->input('name');
        $user->email = $request->input('email');
        $user->role  = $request->input('role');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->phone_number  = $request->input('phone_number');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->save();

        // Ensure a traveler shell exists for traveler role; keep only non-PII fields here
        if ($user->role === 'traveler') {
            $traveler = Traveler::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'bio'  => optional($user->traveler)->bio,
                ]
            );

            // Keep traveler name in sync with user
            if ($traveler->name !== $user->name) {
                $traveler->update(['name' => $user->name]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        /* Prevent self-deletion
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        */

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    /** Export current filtered results as CSV */
    public function export(Request $request)
    {
        $filename = 'users_' . now()->format('Ymd_His') . '.csv';
        $query = $this->filteredUsersQuery($request)->orderBy('id');

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id','name','email','role','phone_number','date_of_birth','created_at']);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $u) {
                    fputcsv($out, [
                        $u->id,
                        $u->name,
                        $u->email,
                        $u->role,
                        $u->phone_number,
                        optional($u->date_of_birth)->format('Y-m-d'),
                        optional($u->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /** Build the filtered users query (shared by index & export). */
    protected function filteredUsersQuery(Request $request)
    {
        $q            = trim((string) $request->query('q', ''));
        $role         = (string) $request->query('role', '');
        $userId       = (string) $request->query('user_id', '');
        $phone        = (string) $request->query('phone', '');
        $dobFrom      = (string) $request->query('dob_from', '');
        $dobTo        = (string) $request->query('dob_to', '');
        $createdFrom  = (string) $request->query('created_from', '');
        $createdTo    = (string) $request->query('created_to', '');

        return User::query()
            ->when($q !== '', function ($qry) use ($q) {
                $qry->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($role !== '', fn ($qry) => $qry->where('role', $role))
            ->when($userId !== '', fn ($qry) => $qry->where('id', $userId))
            ->when($phone !== '', fn ($qry) => $qry->where('phone_number', 'like', "%{$phone}%"))
            ->when($dobFrom !== '', fn ($qry) => $qry->whereDate('date_of_birth', '>=', $dobFrom))
            ->when($dobTo !== '', fn ($qry) => $qry->whereDate('date_of_birth', '<=', $dobTo))
            ->when($createdFrom !== '', fn ($qry) => $qry->whereDate('created_at', '>=', $createdFrom))
            ->when($createdTo !== '', fn ($qry) => $qry->whereDate('created_at', '<=', $createdTo));
    }
}
