<x-guest-layout>
    <div class="w-full max-w-md mx-auto px-8 py-10 bg-white dark:bg-sand-800 rounded-3xl shadow-soft 
                border border-sand-200 dark:border-ink-700 transition-all duration-300 ease-out 
                hover:shadow-glow hover:scale-[1.01]">

        {{-- Header --}}
        <h2 class="text-2xl font-semibold text-center text-ink-900 dark:text-ink-100 mb-2">
            Verify Your Email
        </h2>
        <p class="text-center text-sm text-ink-600 dark:text-ink-300 mb-8">
            Thanks for signing up! Please verify your email address by clicking the link we sent.
            If you didn’t receive the email, we can send you another.
        </p>

        {{-- Status Message --}}
        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 text-center font-medium text-sm text-forest dark:text-forest-light bg-forest/10 dark:bg-forest/20 
                        px-4 py-2 rounded-full border border-forest/30">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-8">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-sm text-ink-600 dark:text-sand-100 hover:text-copper dark:hover:text-copper 
                               transition-all duration-200">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
