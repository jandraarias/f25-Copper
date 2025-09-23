{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
            {{-- Quick actions --}}
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded bg-indigo-600 text-white">View Users</a>
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 rounded bg-green-600 text-white">+ Create User</a>
                <a href="{{ route('admin.users.export') }}" class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600">Export All CSV</a>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Users</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $total }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Travelers</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $counts['traveler'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $percents['traveler'] }}% of users</div>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Experts</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $counts['expert'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $percents['expert'] }}% of users</div>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Businesses</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $counts['business'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $percents['business'] }}% of users</div>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Admins</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $counts['admin'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $percents['admin'] }}% of users</div>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400">New Last 7 Days</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $last7 }}</div>
                </div>

                <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400">New Last 30 Days</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $last30 }}</div>
                </div>
            </div>

            {{-- Breakdown table --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Role Breakdown</h3>
                </div>
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Count</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">% of Users</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach (['traveler' => 'Traveler', 'expert' => 'Expert', 'business' => 'Business', 'admin' => 'Admin'] as $key => $label)
                                <tr>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $label }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $counts[$key] }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $percents[$key] }}%</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 dark:bg-gray-900">
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">Total</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $total }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
