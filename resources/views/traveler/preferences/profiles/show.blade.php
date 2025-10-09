{{-- resources/views/traveler/preference-profiles/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $preferenceProfile->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash-messages />

            <!-- Preferences List -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Preferences</h3>

                @forelse ($preferenceProfile->preferences as $preference)
                    <div class="flex justify-between items-center border-b py-2">
                        <div>
                            <span class="font-medium">{{ $preference->key }}</span>:
                            <span>{{ $preference->value }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('traveler.preferences.edit', $preference) }}"
                               class="px-3 py-1.5 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('traveler.preferences.destroy', $preference) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 border rounded text-red-600 hover:bg-red-50 dark:hover:bg-red-900"
                                        onclick="return confirm('Delete this preference?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No preferences yet.</p>
                @endforelse
            </div>

            <!-- Add Preference -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Add Preference</h3>
                <form method="POST"
                      action="{{ route('traveler.preference-profiles.preferences.store', $preferenceProfile) }}"
                      class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Key (Main Interest)</label>
                        <select id="key-select" name="key" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                            <option value="">-- Select a main interest --</option>
                            @foreach ($allKeys as $key)
                                <option value="{{ $key }}" {{ old('key') === $key ? 'selected' : '' }}>{{ $key }}</option>
                            @endforeach
                        </select>
                        @error('key')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Value (Sub-Interest)</label>
                        <select id="value-select" name="value" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                            <option value="">-- Select a sub-interest --</option> 
                            <!-- dynamically populated -->      
                         </select>
                        @error('value')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Add Preference
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const subInterests = @json($subInterests);
        const mainInterestIds = @json($mainInterestIds);

        const keySelect = document.getElementById('key-select');
        const valueSelect = document.getElementById('value-select');

        keySelect.addEventListener('change', function() {
            const selectedKey = this.value;
            const mainId = mainInterestIds[selectedKey];

            valueSelect.innerHTML = '<option value="">-- Select a sub-interest --</option>';

            if (mainId && subInterests[mainId]) {
                subInterests[mainId].forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.name;
                    option.textContent = sub.name;
                    valueSelect.appendChild(option);
                });
            }
        });

        if (keySelect.value) {
            keySelect.dispatchEvent(new Event('change'));

            const oldValue = "{{ old('value') }}";
            if (oldValue) {
                valueSelect.value = oldValue;
            }
        }
    </script>
</x-app-layout>
