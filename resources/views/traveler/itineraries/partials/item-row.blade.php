{{-- resources/views/traveler/itineraries/partials/item-row.blade.php --}}
@php
    $st = $item->start_time ? \Illuminate\Support\Carbon::parse($item->start_time) : null;
    $et = $item->end_time ? \Illuminate\Support\Carbon::parse($item->end_time) : null;
@endphp

<tr x-data="{ open: false }" class="hover:bg-sand-50 dark:hover:bg-sand-900/40 transition-colors duration-200">
    <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-100">{{ ucfirst($item->type) }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-100">{{ $item->title }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-100">{{ $st ? $st->format('M j, Y g:ia') : '—' }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-ink-800 dark:text-ink-100">{{ $et ? $et->format('M j, Y g:ia') : '—' }}</td>
    <td class="px-4 py-3 whitespace-nowrap text-right">
        <div class="flex items-center gap-2 justify-end">
            <button type="button" @click="open = !open"
                    class="px-3 py-1.5 rounded-full border border-ink-500 text-ink-700 dark:text-ink-200
                           hover:text-copper hover:border-copper hover:shadow-glow hover:scale-[1.03]
                           transition-all duration-200 ease-out text-sm">
                Edit
            </button>

            <form method="POST" action="{{ route('traveler.items.destroy', $item) }}"
                  onsubmit="return confirm('Delete this item?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-3 py-1.5 rounded-full border border-red-400 text-red-500 text-sm
                               hover:bg-red-500 hover:text-white hover:shadow-glow hover:scale-[1.03]
                               transition-all duration-200 ease-out">
                    Delete
                </button>
            </form>
        </div>
    </td>
</tr>

{{-- Inline edit row --}}
<tr x-show="open" x-cloak>
    <td colspan="5" class="px-4 py-6 bg-sand dark:bg-sand-900/40">
        <form method="POST" action="{{ route('traveler.items.update', $item) }}">
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
            <div class="mt-6">
                <button type="submit"
                        class="px-6 py-2.5 rounded-full bg-gradient-copper text-white font-medium shadow-soft
                               hover:shadow-glow hover:scale-[1.03] transition-all duration-200 ease-out">
                    Save Item
                </button>
            </div>
        </form>
    </td>
</tr>
