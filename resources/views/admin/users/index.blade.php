{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Toolbar --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                        <form method="GET" class="flex flex-col sm:flex-row gap-2 sm:items-center">
                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Search name or email…"
                                class="w-full sm:w-64 border rounded px-3 py-2 dark:bg-gray-900 dark:border-gray-700"
                            />

                            <select
                                name="role"
                                class="w-full sm:w-44 border rounded px-3 py-2 dark:bg-gray-900 dark:border-gray-700"
                            >
                                @php $r = request('role'); @endphp
                                <option value="">All roles</option>
                                <option value="traveler" @selected($r==='traveler')>Traveler</option>
                                <option value="expert" @selected($r==='expert')>Expert</option>
                                <option value="business" @selected($r==='business')>Business</option>
                                <option value="admin" @selected($r==='admin')>Admin</option>
                            </select>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded bg-blue-600 text-white px-4 py-2"
                            >
                                Filter
                            </button>
                        </form>

                        <a
                            href="{{ route('admin.users.create') }}"
                            class="inline-flex items-center justify-center rounded bg-green-600 text-white px-4 py-2"
                        >
                            + Create User
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">DOB</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($users as $user)
                                    @php
                                        // Format DOB safely whether it's cast or a string
                                        $dob = $user->date_of_birth
                                            ? \Illuminate\Support\Carbon::parse($user->date_of_birth)->format('M j, Y')
                                            : null;
                                        $role = (string) $user->role;
                                        $colors = [
                                            'traveler' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                            'expert'   => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-200',
                                            'business' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                            'admin'    => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                                        ];
                                        $badge = $colors[$role] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-200';
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-gray-700 dark:text-gray-300">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold {{ $badge }}">
                                                {{ ucfirst($role) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-gray-700 dark:text-gray-300">{{ $user->phone_number ?? '—' }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-gray-700 dark:text-gray-300">{{ $dob ?? '—' }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-gray-700 dark:text-gray-300">
                                                {{ optional($user->created_at)->format('M j, Y') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center gap-2 justify-end">
                                                @if (Route::has('admin.users.edit'))
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                       class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                                                        Edit
                                                    </a>
                                                @endif

                                                @if (Route::has('admin.users.destroy'))
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                          onsubmit="return confirm('Delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-3 py-1.5 rounded bg-red-600 text-white text-sm">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-600 dark:text-gray-300">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
