<!-- resources/views/expert/dashboard.blade.php -->
 
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ __('Local Expert Dashboard') }}
            </h2>
            {{-- Future action: maybe "Update Profile" or "View Travelers" --}}
            <a href="{{ route('expert.profile.edit') }}"
               class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                      hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                Edit Profile
            </a>
        </div>
    </x-slot>

    @php
        $expert             = $expert ?? optional(auth()->user())->expert;
        $itineraries        = $itineraries ?? collect();
        $travelerRequests   = $travelerRequests ?? collect();
        $pendingInvitations = $pendingInvitations ?? collect();
    @endphp

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- Welcome Card --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl p-8 border border-sand-200 dark:border-ink-700
                        transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <p class="text-lg font-semibold text-ink-900 dark:text-ink-100">
                    Welcome, {{ auth()->user()->name }}!
                </p>
                <p class="text-sm text-ink-500 dark:text-sand-100 mt-1">
                    Here’s your expert overview — your assigned itineraries and traveler requests.
                </p>
            </div>

            {{-- ================= Traveler Requests ================= --}}
            {{-- ================= Pending Expert Invitations (Prompt) ================= --}}
            @if($pendingInvitations->isNotEmpty())
                <div class="bg-amber-50 dark:bg-amber-900/20 shadow-soft rounded-3xl border border-amber-200 dark:border-amber-800 p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-amber-200 dark:bg-amber-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 18v.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-ink-900 dark:text-ink-100">You have <span class="pending-invitations-count">{{ $pendingInvitations->count() }}</span> pending itinerary request(s)</h3>
                                <p class="text-sm text-ink-600 dark:text-ink-300">Review and accept a request to gain edit access to a traveler’s itinerary.</p>
                            </div>
                        </div>
                        <a href="{{ route('expert.itinerary-invitations.index') }}" class="text-sm text-amber-700 underline">View all</a>
                    </div>

                    <div class="mt-4 divide-y divide-amber-100 dark:divide-amber-800">
                        @foreach($pendingInvitations->take(3) as $inv)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-ink-900 dark:text-ink-100">{{ $inv->itinerary->name ?? 'Untitled itinerary' }}</p>
                                    <p class="text-xs text-ink-600 dark:text-ink-300">Traveler: {{ $inv->traveler->user->name ?? 'Traveler' }} — Dates: {{ $inv->itinerary->start_date ?? 'TBD' }} → {{ $inv->itinerary->end_date ?? 'TBD' }}</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('expert.itinerary-invitations.accept', $inv) }}" class="ajax-invite-form" data-invitation-id="{{ $inv->id }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-full bg-copper text-white text-sm font-medium">Accept</button>
                                    </form>

                                    <form method="POST" action="{{ route('expert.itinerary-invitations.decline', $inv) }}" class="ajax-invite-form" data-invitation-id="{{ $inv->id }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-full border border-ink-200 text-ink-700 text-sm">Decline</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($travelerRequests->isNotEmpty())
                <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                            p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2-8 4v2h16v-2c0-2-3.582-4-8-4z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">Traveler Requests</h3>
                    </div>

                    <p class="text-sm text-ink-600 dark:text-ink-300 mb-4">
                        Travelers looking for expertise in your region or specialty:
                    </p>

                    <div class="divide-y divide-sand-200 dark:divide-ink-700">
                        @foreach($travelerRequests as $request)
                            <div class="py-4 flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-ink-900 dark:text-ink-100">
                                        {{ $request->traveler->user->name }}
                                    </p>
                                    <p class="text-sm text-ink-500 dark:text-ink-300">
                                        Destination: {{ $request->destination ?? '—' }}
                                    </p>
                                </div>

                                <a href="{{ route('expert.travelers.index') }}"
                                   class="px-4 py-1.5 rounded-full border border-copper text-copper font-medium text-sm
                                          hover:bg-copper hover:text-white hover:shadow-glow transition-all duration-200 ease-out">
                                    View
                                </a>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endif

            {{-- ================= Assigned Itineraries ================= --}}
            <div class="bg-white dark:bg-sand-800 shadow-soft rounded-3xl border border-sand-200 dark:border-ink-700
                        p-8 transition-all duration-200 ease-out hover:shadow-glow hover:scale-[1.01]">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-copper/10 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-copper" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 7.5l8.954-4.477a1.125 1.125 0 011.092 0L21.25 7.5M2.25 7.5v9a1.125 1.125 0 00.598.995l8.954 4.477a1.125 1.125 0 001.092 0l8.954-4.477a1.125 1.125 0 00.598-.995v-9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-ink-900 dark:text-ink-100">Assigned Itineraries</h3>
                </div>

                @forelse ($itineraries as $itinerary)

                    @php
                        $sd = $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('M d, Y') : '—';
                        $ed = $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('M d, Y') : '—';
                    @endphp

                    <div class="mb-6 pb-4 border-b border-sand-200 dark:border-ink-700 last:border-0 last:pb-0">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <div>
                                <p class="font-semibold text-ink-900 dark:text-ink-100 text-lg">
                                    {{ $itinerary->name }}
                                </p>
                                <p class="text-sm text-ink-500 dark:text-sand-100">
                                    {{ $sd }} → {{ $ed }}
                                </p>
                            </div>

                            <a href="{{ route('expert.itineraries.index') }}"
                               class="group inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-copper text-copper font-medium text-sm
                                      hover:bg-copper hover:text-white hover:shadow-glow transition-all duration-200 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                        </div>

                        {{-- List a preview of itinerary items --}}
                        @if($itinerary->items && $itinerary->items->count())
                            <ul class="list-disc ml-6 mt-3 text-sm text-ink-700 dark:text-sand-100">
                                @foreach ($itinerary->items->take(3) as $item)
                                    <li>
                                        <span class="font-medium">{{ ucfirst($item->type) }}</span>:
                                        {{ $item->title }}
                                        @if(!empty($item->location))
                                            — {{ $item->location }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            @if($itinerary->items->count() > 3)
                                <p class="text-xs text-ink-500 dark:text-ink-400 mt-1 italic">…and more</p>
                            @endif
                        @else
                            <p class="text-sm text-ink-500 dark:text-ink-400 mt-2 italic">No items yet.</p>
                        @endif
                    </div>

                @empty
                    <p class="text-sm text-ink-500 dark:text-sand-100 italic">No itineraries assigned yet.</p>
                @endforelse
            </div>

        </div>
    </div>
        {{-- AJAX handlers for accept/decline invitations --}}
        <script>
            (function(){
                const csrf = '{{ csrf_token() }}';

                function updatePendingCount(delta) {
                    const el = document.querySelector('.pending-invitations-count');
                    if (!el) return;
                    const current = parseInt(el.textContent || '0', 10);
                    el.textContent = Math.max(0, current + delta);
                }

                document.querySelectorAll('.ajax-invite-form').forEach(form => {
                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const btn = form.querySelector('button[type="submit"]');
                        if (btn) btn.disabled = true;
                        const card = form.closest('.py-3');

                        try {
                            const res = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin'
                            });

                            if (!res.ok) {
                                const txt = await res.text();
                                console.error('Invite request failed', res.status, txt);
                                alert('Failed to process request. Please try again.');
                                if (btn) btn.disabled = false;
                                return;
                            }

                            const data = await res.json();
                            // Remove the invitation card from the UI
                            if (card) card.remove();
                            // Decrement pending count if present
                            updatePendingCount(-1);

                            // Optionally redirect when accepted to itinerary show
                            if (data.status === 'accepted' && data.itinerary) {
                                // navigate to the itinerary show page to give immediate access
                                // Commented out by default; uncomment to auto-redirect:
                                // window.location.href = `/expert/itineraries/${data.itinerary.id}`;
                            }
                        } catch (err) {
                            console.error(err);
                            alert('An error occurred. Please try again.');
                            if (btn) btn.disabled = false;
                        }
                    });
                });
            })();
        </script>
</x-app-layout>
