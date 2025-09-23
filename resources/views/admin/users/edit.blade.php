{{-- resources/views/admin/users/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" x-data="{ role: @js(old('role', $user->role)) }">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium">Name</label>
                                <input name="name" type="text" value="{{ old('name', $user->name) }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Email</label>
                                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Role</label>
                                <select name="role" x-model="role" class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                    @php $r = old('role', $user->role); @endphp
                                    <option value="traveler" @selected($r==='traveler')>Traveler</option>
                                    <option value="expert" @selected($r==='expert')>Expert</option>
                                    <option value="business" @selected($r==='business')>Business</option>
                                    <option value="admin" @selected($r==='admin')>Admin</option>
                                </select>
                                @error('role')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">New Password (optional)</label>
                                <input name="password" type="password" class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" autocomplete="new-password">
                                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Confirm Password</label>
                                <input name="password_confirmation" type="password" class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" autocomplete="new-password">
                            </div>

                            {{-- Traveler-only fields (required if role = traveler) --}}
                            <div class="col-span-1" x-show="role === 'traveler'">
                                <label class="block text-sm font-medium">Date of Birth</label>
                                <input name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($user->traveler)->date_of_birth) }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('date_of_birth')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="col-span-1" x-show="role === 'traveler'">
                                <label class="block text-sm font-medium">Phone Number</label>
                                <input name="phone_number" type="tel" value="{{ old('phone_number', optional($user->traveler)->phone_number) }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700">
                                @error('phone_number')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-3">
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Save</button>

                            <a href="{{ route('admin.users.index') }}"
                               class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <div class="mt-6">
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white">
                                Delete User
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
