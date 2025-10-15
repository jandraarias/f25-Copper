{{-- resources/views/components/form/item-fields.blade.php --}}
@props(['old' => []])

{{-- Shared fields for itinerary items --}}
<div>
    <label class="block text-sm font-semibold text-ink-700 dark:text-ink-300 mb-2">Type</label>
    <select name="type" required
            class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                   focus:ring-copper focus:border-copper focus:shadow-glow
                   transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
        @php $t = old('type', $old['type'] ?? ''); @endphp
        <option value="">Select…</option>
        <option value="flight"   @selected($t === 'flight')>Flight</option>
        <option value="hotel"    @selected($t === 'hotel')>Hotel</option>
        <option value="activity" @selected($t === 'activity')>Activity</option>
        <option value="transfer" @selected($t === 'transfer')>Transfer</option>
        <option value="note"     @selected($t === 'note')>Note</option>
    </select>
    @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-semibold text-ink-700 dark:text-ink-300 mb-2">Title</label>
    <input name="title" type="text" value="{{ old('title', $old['title'] ?? '') }}" required
           class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                  focus:ring-copper focus:border-copper focus:shadow-glow
                  transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-semibold text-ink-700 dark:text-ink-300 mb-2">Start Time</label>
    <input name="start_time" type="datetime-local" value="{{ old('start_time', $old['start_time'] ?? '') }}" required
           class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                  focus:ring-copper focus:border-copper focus:shadow-glow
                  transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
    @error('start_time')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-semibold text-ink-700 dark:text-ink-300 mb-2">End Time</label>
    <input name="end_time" type="datetime-local" value="{{ old('end_time', $old['end_time'] ?? '') }}"
           class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                  focus:ring-copper focus:border-copper focus:shadow-glow
                  transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
    @error('end_time')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-semibold text-ink-700 dark:text-ink-300 mb-2">Location</label>
    <input name="location" type="text" value="{{ old('location', $old['location'] ?? '') }}"
           class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                  focus:ring-copper focus:border-copper focus:shadow-glow
                  transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">
    @error('location')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-semibold text-ink-700 dark:text-ink-300 mb-2">Details</label>
    <textarea name="details" rows="4"
              class="w-full border border-sand-200 dark:border-ink-700 rounded-xl shadow-sm
                     focus:ring-copper focus:border-copper focus:shadow-glow
                     transition-all duration-200 px-4 py-2.5 dark:bg-sand-900">{{ old('details', $old['details'] ?? '') }}</textarea>
    @error('details')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>
