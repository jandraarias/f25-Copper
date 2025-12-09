{{-- resources/views/expert/itineraries/item-row-edit.blade.php --}}
@php
    use Illuminate\Support\Carbon;

    $st = $item->start_time ? Carbon::parse($item->start_time) : null;
    $et = $item->end_time ? Carbon::parse($item->end_time) : null;
@endphp

<div x-data="{ open: false, expanded: false }"
     class="border border-sand-200 dark:border-ink-700 bg-white dark:bg-sand-800
            rounded-2xl shadow-soft hover:shadow-glow transition-all duration-200 p-5">

    {{-- HEADER (click to expand) --}}
    <button @click="open = !open"
            class="w-full flex items-center justify-between text-left">
        <div class="min-w-0">
            <h4 class="font-semibold text-ink-900 dark:text-sand-100 truncate">
                {{ $item->title }}
            </h4>

            <p class="text-sm text-ink-600 dark:text-ink-300 mt-1 truncate">
                {{ ucfirst($item->type) }}

                @if($st)
                    • {{ $st->format('M j, g:ia') }}
                @endif

                @if($et)
                    - {{ $et->format('g:ia') }}
                @endif
            </p>
        </div>

        <svg :class="{ 'rotate-180' : open }"
             class="w-5 h-5 text-ink-700 dark:text-ink-200 transition-transform duration-300"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- COLLAPSED SUMMARY (visible when not editing) --}}
    <div x-show="!open" class="mt-3 text-sm text-ink-700 dark:text-sand-200 line-clamp-2">
        @if($item->details)
            {{ $item->details }}
        @else
            <em class="text-ink-500">No details provided</em>
        @endif
    </div>

    {{-- EXPANDED EDITING AREA --}}
    <div x-show="open" x-collapse class="mt-6 border-t pt-6 border-sand-200 dark:border-ink-700">

        {{-- INLINE EDIT FORM --}}
        <form method="POST" action="{{ route('expert.items.update', $item) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Type --}}
                <div>
                    <label class="block text-xs font-semibold mb-1 text-ink-600 dark:text-ink-300">
                        Type
                    </label>
                    <input type="text" name="type"
                           value="{{ old('type', $item->type) }}"
                           class="w-full border border-sand-300 dark:border-ink-600 rounded-xl
                                  px-3 py-2 dark:bg-sand-900">
                </div>

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-semibold mb-1">Title</label>
                    <input type="text" name="title"
                           value="{{ old('title', $item->title) }}"
                           class="w-full border rounded-xl px-3 py-2 dark:bg-sand-900">
                </div>

                {{-- Start Time --}}
                <div>
                    <label class="block text-xs font-semibold mb-1">Start Time</label>
                    <input type="datetime-local" name="start_time"
                           value="{{ $st ? $st->format('Y-m-d\TH:i') : '' }}"
                           class="w-full border rounded-xl px-3 py-2 dark:bg-sand-900">
                </div>

                {{-- End Time --}}
                <div>
                    <label class="block text-xs font-semibold mb-1">End Time</label>
                    <input type="datetime-local" name="end_time"
                           value="{{ $et ? $et->format('Y-m-d\TH:i') : '' }}"
                           class="w-full border rounded-xl px-3 py-2 dark:bg-sand-900">
                </div>

                {{-- Location --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold mb-1">Location</label>
                    <input type="text" name="location"
                           value="{{ old('location', $item->location) }}"
                           class="w-full border rounded-xl px-3 py-2 dark:bg-sand-900">
                </div>

                {{-- Details --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold mb-1">Details</label>
                    <textarea name="details" rows="3"
                              class="w-full border rounded-xl px-3 py-2 dark:bg-sand-900">{{ old('details', $item->details) }}</textarea>
                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex justify-end gap-3 pt-2">

                <button type="button" @click="open = false"
                        class="px-5 py-2 rounded-full border border-ink-400 text-ink-700 dark:text-ink-200
                               hover:border-copper hover:text-copper transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-6 py-2 rounded-full bg-gradient-copper text-white font-semibold shadow-soft
                               hover:shadow-glow hover:scale-[1.03] transition">
                    Save Item
                </button>

                <form method="POST" action="{{ route('expert.items.destroy', $item) }}"
                      onsubmit="return confirm('Delete this item?');">
                    @csrf
                    @method('DELETE')
                    <button class="px-5 py-2 rounded-full border border-red-400 text-red-500
                                   hover:bg-red-500 hover:text-white transition">
                        Delete
                    </button>
                </form>

            </div>
        </form>
    </div>
</div>
