{{-- resources/views/admin/users/create.blade.php --}}

<x-app-layout>

    {{-- ---------------------------------------------------------------
         FLATPICKR — Beautiful date picker
    ---------------------------------------------------------------- --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- ---------------------------------------------------------------
         Custom Copper Flatpickr Theme
    ---------------------------------------------------------------- --}}
    <style>
        .flatpickr-calendar {
            border-radius: 1rem !important;
            border: 1px solid #e0d5c3 !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
        }
        .flatpickr-months .flatpickr-month {
            background: #f7f4ef !important;
        }
        .flatpickr-current-month input.cur-year {
            color: #8a5b35 !important;
            font-weight: 600 !important;
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: linear-gradient(135deg, #c67c48, #dca577) !important;
            color: white !important;
            border-color: #c67c48 !important;
        }
        .flatpickr-day:hover {
            background: rgba(198,124,72,0.18) !important;
            color: #c67c48 !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.users.store') }}"
                          x-data="{ role: @js(old('role', 'traveler')) }" x-cloak>
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Name --}}
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium">Name</label>
                                <input name="name" type="text" value="{{ old('name') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" required>
                                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-medium">Email</label>
                                <input name="email" type="email" value="{{ old('email') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" required>
                                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Role --}}
                            <div>
                                <label class="block text-sm font-medium">Role</label>
                                <select name="role" x-model="role"
                                        class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700" required>
                                    <option value="traveler">Traveler</option>
                                    <option value="expert">Expert</option>
                                    <option value="business">Business</option>
                                    <option value="admin">Admin</option>
                                </select>
                                @error('role')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <label class="block text-sm font-medium">Password</label>
                                <input name="password" type="password"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                       required autocomplete="new-password">
                                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label class="block text-sm font-medium">Confirm Password</label>
                                <input name="password_confirmation" type="password"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                       required autocomplete="new-password">
                            </div>

                            {{-- Phone Number (hidden when admin) --}}
                            <div class="col-span-1" x-show="role !== 'admin'">
                                <label class="block text-sm font-medium">Phone Number</label>
                                <input name="phone_number" type="tel" value="{{ old('phone_number') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                       x-bind:required="role !== 'admin'"
                                       x-bind:disabled="role === 'admin'">
                                @error('phone_number')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Date of Birth (Flatpickr) --}}
                            <div class="col-span-1" x-show="role !== 'business' && role !== 'admin'">
                                <label class="block text-sm font-medium">Date of Birth</label>

                                <input name="date_of_birth"
                                       id="dob_picker"
                                       type="text"
                                       placeholder="YYYY-MM-DD"
                                       value="{{ old('date_of_birth') }}"
                                       class="mt-1 w-full border rounded p-2 dark:bg-gray-900 dark:border-gray-700"
                                       x-bind:required="role !== 'business' && role !== 'admin'"
                                       x-bind:disabled="role === 'business' || role === 'admin'">

                                @error('date_of_birth')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>

                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">
                                Create
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- ---------------------------------------------------------------
         FLATPICKR SCRIPT — Activation Logic
    ---------------------------------------------------------------- --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            flatpickr("#dob_picker", {
                dateFormat: "Y-m-d",
                allowInput: true, // allow typing
                maxDate: "today"  // DOB cannot be in the future
            });
        });
    </script>

</x-app-layout>
