<section class="space-y-6">

    {{-- Section Header --}}
    <header>
        <h2 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-ink-600 dark:text-sand-200 leading-relaxed">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- Resend Verification Form --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Update Profile Form --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-8">
        @csrf
        @method('patch')


        {{--  NAME --}}
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Name')" />

            <x-text-input id="name" name="name" type="text"
                class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                       bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                       focus:ring focus:ring-copper/30 focus:border-copper transition"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />

            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>


        {{--  EMAIL --}}
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input id="email" name="email" type="email"
                class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                       bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                       focus:ring focus:ring-copper/30 focus:border-copper transition"
                :value="old('email', $user->email)" required autocomplete="username" />

            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            {{--  Email Verification --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2 space-y-1">

                    <p class="text-sm text-ink-800 dark:text-sand-100">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-copper hover:text-copper/80
                                   font-medium rounded-md focus:outline-none focus:ring-2
                                   focus:ring-copper/40 dark:focus:ring-offset-sand-900">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-sm font-medium text-forest dark:text-forest/80">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif

                </div>
            @endif
        </div>


        {{--  TRAVELER / EXPERT FIELDS --}}
        @if (in_array($user->role, ['traveler', 'expert']))

            {{-- Phone --}}
            <div class="space-y-2">
                <x-input-label for="phone_number" :value="__('Phone Number')" />

                <x-text-input id="phone_number" name="phone_number" type="text"
                    class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                           bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                           focus:ring focus:ring-copper/30 focus:border-copper transition"
                    :value="old('phone_number', $user->phone_number)" required />

                <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
            </div>

            {{-- DOB (read-only) --}}
            <div class="space-y-2">
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />

                <x-text-input id="date_of_birth" name="date_of_birth" type="date"
                    class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                           bg-sand-100 dark:bg-ink-800 text-ink-700 dark:text-sand-300
                           cursor-not-allowed opacity-80"
                    :value="old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d'))" readonly />

                <p class="text-sm text-ink-500 dark:text-sand-300">
                    {{ __('Contact an administrator if your date of birth is incorrect.') }}
                </p>
            </div>

            {{-- Bio --}}
            <div class="space-y-2">
                <x-input-label for="bio" :value="__('Bio')" />

                <textarea id="bio" name="bio" rows="3"
                    class="block w-full px-4 py-3 rounded-xl border border-sand-300 dark:border-ink-700
                           bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                           focus:ring focus:ring-copper/30 focus:border-copper transition sm:text-sm">{{ old('bio', $user->traveler?->bio) }}</textarea>

                <x-input-error class="mt-1" :messages="$errors->get('bio')" />
            </div>

        {{--  BUSINESS USERS --}}
        @elseif ($user->role === 'business')

            {{-- Business phone --}}
            <div class="space-y-2">
                <x-input-label for="phone_number" :value="__('Phone Number')" />

                <x-text-input id="phone_number" name="phone_number" type="text"
                    class="block w-full px-4 py-2.5 rounded-xl border border-sand-300 dark:border-ink-700
                           bg-white dark:bg-sand-900 text-ink-800 dark:text-sand-100
                           focus:ring focus:ring-copper/30 focus:border-copper transition"
                    :value="old('phone_number', $user->phone_number)" required />

                <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
            </div>

        @endif


        {{--  Save Button + Success --}}
        <div class="flex items-center gap-4 pt-2">

            {{-- Save --}}
            <button type="submit"
                class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                       hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                {{ __('Save') }}
            </button>

            {{-- Success --}}
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition.opacity.duration.300ms
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm font-medium text-forest dark:text-forest/80">
                    {{ __('Saved.') }}
                </p>
            @endif

        </div>

    </form>
</section>
