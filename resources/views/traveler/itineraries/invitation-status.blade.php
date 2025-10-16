<x-app-layout>
    <div class="py-20 text-center max-w-lg mx-auto">
        <h1 class="text-2xl font-semibold text-copper mb-4">Invitation Update</h1>
        <p class="text-ink-700 dark:text-ink-200">{{ $message }}</p>
        <a href="{{ route('dashboard') }}" class="mt-6 inline-block px-6 py-2.5 bg-gradient-copper text-white rounded-full shadow-soft hover:shadow-glow transition">
            Go to Dashboard
        </a>
    </div>
</x-app-layout>
