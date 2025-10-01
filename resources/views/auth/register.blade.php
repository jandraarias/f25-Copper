<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4" x-data="{ role: '{{ old('role','') }}' }">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full" x-model="role" required>
                <option value="">-- Select Role --</option>
                <option value="traveler" {{ old('role') === 'traveler' ? 'selected' : '' }}>Traveler</option>
                <option value="expert" {{ old('role') === 'expert' ? 'selected' : '' }}>Expert</option>
                <option value="business" {{ old('role') === 'business' ? 'selected' : '' }}>Business</option>
                {{-- No admin option here --}}
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />

            <!-- Phone Number (traveler, expert, business) -->
            <div class="mt-4" x-show="['traveler','expert','business'].indexOf(role) !== -1" x-cloak>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                    :value="old('phone_number')" />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <!-- Date of Birth (traveler, expert only) -->
            <div class="mt-4" x-show="['traveler','expert'].indexOf(role) !== -1" x-cloak>
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth"
                    :value="old('date_of_birth')" />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 
                      rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 
                      dark:focus:ring-offset-gray-800" 
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<!-- Prevent Alpine from flashing hidden fields -->
<style>
    [x-cloak] { display: none !important; }
</style>
