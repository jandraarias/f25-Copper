{{-- resources/views/traveler/itineraries/partials/item-row-edit.blade.php --}}
@php
    use Illuminate\Support\Carbon;

    $st = $item->start_time ? Carbon::parse($item->start_time) : null;
    $et = $item->end_time ? Carbon::parse($item->end_time) : null;
@endphp

{{-- Quick Add Row (only visible when table is first loaded in edit view) --}}
@if ($loop->first)
<tr x-data="{ addOpen: false }" x-cloak>
    <td colspan="5" class="bg-sand-50 dark:bg-sand-900/40 p-4 border-b border-sand-200 dark:border-ink-700">
        <button type="button"
                @click="addOpen = !addOpen"
                class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-full border border-copper text-copper
                       hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.02]
                       transition-all duration-200 ease-out font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span x-show="!addOpen">Quick Add Item</span>
            <span x-show="addOpen">Cancel</span>
        </button>

        <div x-show="addOpen" x-transition.opacity.duration.200ms class="mt-6">
            <form method="POST" action="{{ route('traveler.itineraries.items.store', $itinerary) }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div><input name="type" type="text" placeholder="Type (e.g., Activity)" class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm" required></div>
                <div class="md:col-span-2"><input name="title" type="text" placeholder="Title (e.g., Museum Visit)" class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm" required></div>
                <div><input name="start_time" type="datetime-local" class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm"></div>
                <div><input name="end_time" type="datetime-local" class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm"></div>
                <div class="md:col-span-2"><input name="location" type="text" placeholder="Location (optional)" class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm"></div>
                <div class="md:col-span-3"><input name="details" type="text" placeholder="Details (optional)" class="w-full border rounded-xl shadow-sm px-3 py-2 text-sm"></div>
                <div class="md:col-span-5 flex justify-end mt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft hover:shadow-glow hover:scale-[1.03]">Add Item</button>
                </div>
            </form>
        </div>
    </td>
</tr>
@endif

{{-- Existing Item Row --}}
<tr x-data="{ open: false, expanded: false }" class="group hover:bg-sand-50 dark:hover:bg-sand-900/40 transition-all duration-300 ease-in-out">
    <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-100 font-medium">
        {{ ucfirst($item->type) }}
    </td>
    <td class="px-4 py-3 max-w-xs text-ink-800 dark:text-ink-100">
        <div class="flex flex-col">
            <span class="font-semibold truncate">{{ $item->title }}</span>
            @if ($item->details)
                <p x-bind:class="expanded ? 'line-clamp-none' : 'line-clamp-2'"
                   class="text-sm text-ink-600 dark:text-sand-100 mt-1 transition-all duration-200 ease-in-out break-words">
                    {{ $item->details }}
                </p>
                @if (Str::length($item->details) > 120)
                    <button type="button" @click="expanded = !expanded"
                            class="mt-1 text-xs text-copper hover:underline focus:outline-none self-start">
                        <span x-show="!expanded">Read more</span>
                        <span x-show="expanded">Show less</span>
                    </button>
                @endif
            @endif
        </div>
    </td>
    <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-100">{{ $st ? $st->format('M j, Y g:ia') : '—' }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-100">{{ $et ? $et->format('M j, Y g:ia') : '—' }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-right">
        <div class="flex items-center gap-2 justify-end">
            <button type="button" @click="open = !open" class="px-3 py-1.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200 hover:text-copper hover:border-copper hover:shadow-glow hover:scale-[1.03] text-sm">
                <span x-show="!open">Edit</span>
                <span x-show="open">Close</span>
            </button>
            <form method="POST" action="{{ route('traveler.items.destroy', $item) }}" onsubmit="return confirm('Delete this item?');">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-1.5 rounded-full border border-red-400 text-red-500 text-sm hover:bg-red-500 hover:text-white hover:shadow-glow hover:scale-[1.03]">
                    Delete
                </button>
            </form>
        </div>
    </td>
</tr>

{{-- Inline Edit Row --}}
<tr x-show="open" x-transition x-cloak>
    <td colspan="5" class="px-6 py-6 bg-sand-50 dark:bg-sand-900/50 border-t border-sand-200 dark:border-ink-700 rounded-b-2xl shadow-inner">
        <form method="POST" action="{{ route('traveler.items.update', $item) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.item-fields :old="[
                    'type' => $item->type,
                    'title' => $item->title,
                    'start_time' => $st ? $st->format('Y-m-d\TH:i') : '',
                    'end_time' => $et ? $et->format('Y-m-d\TH:i') : '',
                    'location' => $item->location,
                    'details' => $item->details,
                ]" />
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" @click="open = false" class="px-5 py-2.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200 text-sm hover:text-copper hover:border-copper hover:shadow-glow hover:scale-[1.03]">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft hover:shadow-glow hover:scale-[1.03]">
                    Save Item
                </button>
            </div>
        </form>
    </td>
</tr>
