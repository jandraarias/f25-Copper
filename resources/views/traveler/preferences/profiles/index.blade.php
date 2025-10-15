{{-- resources/views/traveler/preference/profiles/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('My Preference Profiles') }}
            </h2>
            <a href="{{ route('traveler.preference-profiles.create') }}"
               class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium
                      shadow-soft hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out dark:shadow-glow-dark">
                + New Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-flash-messages />

            {{-- Info Card --}}
            <div class="bg-white dark:bg-sand-800 text-ink-900 dark:text-ink-200
                        shadow-soft dark:shadow-glow-dark rounded-3xl p-8
                        border border-sand-200 dark:border-ink-700
                        transition-all duration-200 ease-out">
                <p class="text-ink-700 dark:text-ink-200/80 leading-relaxed">
                    Manage your travel preference profiles below. Each profile helps us tailor your trips
                    based on your favorite activities, budget, and lifestyle preferences.
                </p>
            </div>

            {{-- Profile List --}}
            <div class="bg-white dark:bg-sand-800 text-ink-900 dark:text-ink-200
                        shadow-soft dark:shadow-glow-dark rounded-3xl
                        border border-sand-200 dark:border-ink-700 p-8
                        transition-all duration-200 ease-out">
                <h3 class="text-lg font-semibold text-copper mb-6">Your Profiles</h3>

                @forelse ($profiles as $profile)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between
                                border-b border-sand-200 dark:border-ink-700
                                py-5 last:border-0 transition-colors duration-200">
                        {{-- Profile Info --}}
                        <div>
                            <h4 class="text-xl font-semibold text-ink-900 dark:text-ink-100">
                                {{ $profile->name }}
                            </h4>
                            <p class="text-sm text-ink-500 dark:text-ink-300">
                                {{ $profile->preferences->count() }}
                                {{ Str::plural('preference', $profile->preferences->count()) }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap gap-2 mt-3 sm:mt-0">
                            {{-- View --}}
                            <a href="{{ route('traveler.preference-profiles.show', $profile) }}"
                               class="group flex items-center gap-2 px-4 py-1.5 rounded-full
                                      border border-copper text-copper font-medium text-sm
                                      hover:bg-copper hover:text-white hover:shadow-glow
                                      hover:scale-[1.03] transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-4 h-4 transition-colors duration-200 group-hover:text-white"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                             9.542 7-1.274 4.057-5.064 7-9.542 7-4.477
                                             0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>View</span>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('traveler.preference-profiles.edit', $profile) }}"
                               class="group flex items-center gap-2 px-4 py-1.5 rounded-full
                                      border border-forest text-forest font-medium text-sm
                                      hover:bg-forest hover:text-white hover:shadow-glow
                                      hover:scale-[1.03] transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-4 h-4 transition-colors duration-200 group-hover:text-white"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.232 5.232a3 3 0 114.243 4.243L7.5
                                             21H3v-4.5l12.232-11.268z"/>
                                </svg>
                                <span>Edit</span>
                            </a>

                            {{-- Delete --}}
                            <form method="POST"
                                  action="{{ route('traveler.preference-profiles.destroy', $profile) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this profile?')"
                                        class="group flex items-center gap-2 px-4 py-1.5 rounded-full
                                               border border-red-400 text-red-500 font-medium text-sm
                                               hover:bg-red-500 hover:text-white hover:shadow-glow
                                               hover:scale-[1.03] transition-all duration-200 ease-out">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-4 h-4 transition-colors duration-200 group-hover:text-white"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-ink-500 dark:text-ink-300 text-sm italic mb-3">
                            You haven’t created any preference profiles yet.
                        </p>
                        <a href="{{ route('traveler.preference-profiles.create') }}"
                           class="group inline-flex items-center gap-2 px-6 py-2.5 rounded-full
                                  bg-gradient-copper text-white font-medium
                                  shadow-soft hover:shadow-glow hover:scale-[1.03]
                                  transition-all duration-200 ease-out dark:shadow-glow-dark">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 transition-transform duration-200 group-hover:rotate-90"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Create Your First Profile</span>
                        </a>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($profiles->hasPages())
                    <div class="mt-8 border-t border-sand-200 dark:border-ink-700 pt-6">
                        {{ $profiles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
