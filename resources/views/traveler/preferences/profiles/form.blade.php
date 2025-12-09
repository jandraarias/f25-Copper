{{-- resources/views/traveler/preferences/profiles/form.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-ink-900 dark:text-ink-200">
                {{ isset($preferenceProfile) ? 'Edit Preference Profile' : 'Create Preference Profile' }}
            </h2>
            <a href="{{ route('traveler.preference-profiles.index') }}"
               class="group flex items-center gap-2 px-4 py-2 rounded-full border border-copper text-copper 
                      hover:bg-copper hover:text-white hover:shadow-glow hover:scale-[1.03]
                      transition-all duration-200 ease-out font-medium shadow-soft dark:shadow-glow-dark">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Profiles
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-sand dark:bg-sand-900 min-h-screen transition-colors duration-300" x-data="{ showToast: false }">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <x-flash-messages />

            <div class="bg-white dark:bg-sand-800 text-ink-900 dark:text-ink-200
                        shadow-soft dark:shadow-glow-dark rounded-3xl p-8
                        border border-sand-200 dark:border-ink-700
                        hover:shadow-glow hover:scale-[1.005]
                        transition-all duration-200 ease-out">

                <form 
                    x-data="{ isSubmitting: false }"
                    x-on:submit.prevent="
                        isSubmitting = true;
                        $el.submit();
                        setTimeout(() => { showToast = true }, 600);
                        setTimeout(() => { showToast = false }, 3500);
                    "
                    method="POST"
                    action="{{ isset($preferenceProfile)
                        ? route('traveler.preference-profiles.update', $preferenceProfile)
                        : route('traveler.preference-profiles.store') }}"
                >
                    @csrf
                    @if(isset($preferenceProfile))
                        @method('PUT')
                    @endif

                    {{-- Minimum preferences error (your $validator->after(...) message) --}}
                    @error('preferences')
                        <div class="p-4 mb-6 rounded-2xl border border-red-200 bg-red-50 text-red-900 shadow-soft">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- Profile Name --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-ink-700 dark:text-ink-200 mb-2">Profile Name</label>
                        <input type="text" name="name"
                               value="{{ old('name', $preferenceProfile->name ?? '') }}"
                               class="w-full border border-sand-200 dark:border-ink-700
                                      bg-white dark:bg-sand-900 text-ink-900 dark:text-ink-100
                                      rounded-xl shadow-sm focus:ring-copper focus:border-copper
                                      focus:shadow-glow transition-all duration-200"
                               placeholder="My Adventure Preferences" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- === Tab System === --}}
                    <div x-data="{ activeTab: 'activities' }" class="space-y-8">

                        {{-- Tab Navigation --}}
                        <div class="flex justify-center mb-6">
                            <nav class="flex flex-wrap justify-center bg-sand dark:bg-sand-800
                                        rounded-full p-1 shadow-soft dark:shadow-glow-dark transition-colors duration-300">
                                @foreach ([
                                    'activities' => 'Activities',
                                    'budget' => 'Budget',
                                    'dietary' => 'Dietary',
                                    'cuisine' => 'Cuisine',
                                ] as $tab => $label)
                                    <button type="button" 
                                            @click="activeTab = '{{ $tab }}'"
                                            :class="activeTab === '{{ $tab }}'
                                                ? 'bg-gradient-copper text-white shadow-glow scale-[1.05]'
                                                : 'text-ink-700 dark:text-ink-200 hover:text-copper'"
                                            class="px-5 py-2.5 rounded-full text-sm font-medium
                                                   transition-all duration-200 ease-out">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </nav>
                        </div>

                        {{-- ========= Activities (Collapsible Cards) ========= --}}
                        <div x-cloak x-show="activeTab === 'activities'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            <h3 class="text-lg font-semibold text-copper mb-3">Activity Preferences</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-200/70 mb-5">
                                Expand a category to choose sub-interests. Selected items appear inside each card <em>and</em> in the global summary.
                            </p>

                            @php
                                // Build filtered main list and sub map (exclude Cuisine, Dietary Restrictions, and Budget & Price Level)
                                $filteredMains = $mainOptions
                                    ->reject(fn($m) => in_array($m->name, [
                                        'Cuisine',
                                        'Dietary Restrictions',
                                        'Budget & Price Level',
                                    ]))
                                    ->map(fn($m) => ['id' => $m->id, 'name' => $m->name])
                                    ->values();

                                $filteredSubMap = collect($subMap)
                                    ->reject(function ($subs, $mainId) use ($mainOptions) {
                                        $main = $mainOptions->firstWhere('id', $mainId);
                                        return $main && $main->name === 'Budget & Price Level';
                                    })
                                    ->all();

                                $preSelectedActivityIds = $preferences
                                    ->where('key', 'activity')
                                    ->map(fn($p) => \App\Models\PreferenceOption::where('name', $p->value)->value('id'))
                                    ->filter()
                                    ->values();
                            @endphp

                            <div
                              x-data="{
                                  mains: @js($filteredMains),
                                  subMap: @js($filteredSubMap),
                                  openMainId: null,
                                  selectedSubs: @js($preSelectedActivityIds),
                                  isSelected(id) { return this.selectedSubs.includes(id) },
                                  toggleSub(id) {
                                      if (this.isSelected(id)) {
                                          this.selectedSubs = this.selectedSubs.filter(s => s !== id);
                                      } else {
                                          this.selectedSubs.push(id);
                                      }
                                  },
                                  selectAll(mainId) {
                                      const all = (this.subMap[mainId] || []).map(s => s.id);
                                      const toAdd = all.filter(id => !this.selectedSubs.includes(id));
                                      this.selectedSubs = this.selectedSubs.concat(toAdd);
                                  },
                                  clearAll(mainId) {
                                      const all = (this.subMap[mainId] || []).map(s => s.id);
                                      this.selectedSubs = this.selectedSubs.filter(id => !all.includes(id));
                                  },
                                  subName(id) {
                                      for (const arr of Object.values(this.subMap)) {
                                          const f = arr.find(s => s.id === id);
                                          if (f) return f.name;
                                      }
                                      return '';
                                  }
                              }"
                              class="space-y-4"
                            >
                                {{-- Cards --}}
                                <template x-for="m in mains" :key="m.id">
                                    <div class="rounded-2xl border border-sand-200 dark:border-ink-700 bg-white dark:bg-sand-900 shadow-sm">
                                        <button type="button"
                                                @click="openMainId = (openMainId === m.id ? null : m.id)"
                                                class="w-full flex items-center justify-between px-5 py-4">
                                            <span class="text-base font-semibold text-ink-900 dark:text-ink-100" x-text="m.name"></span>
                                            <svg x-show="openMainId !== m.id" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                                            </svg>
                                            <svg x-show="openMainId === m.id" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5" />
                                            </svg>
                                        </button>

                                        <div x-show="openMainId === m.id"
                                             x-collapse
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             class="px-5 pb-5">
                                            {{-- Actions --}}
                                            <div class="flex items-center gap-2 mb-3">
                                                <button type="button"
                                                    @click="selectAll(m.id)"
                                                    class="px-3 py-1.5 rounded-full text-sm font-medium border border-copper text-copper hover:bg-copper hover:text-white transition">
                                                    Select all
                                                </button>
                                                <button type="button"
                                                    @click="clearAll(m.id)"
                                                    class="px-3 py-1.5 rounded-full text-sm font-medium border border-ink-400 text-ink-700 dark:text-ink-200 hover:border-copper hover:text-copper transition">
                                                    Clear all
                                                </button>
                                            </div>

                                            {{-- Unselected vs Selected --}}
                                            <div class="grid sm:grid-cols-2 gap-4">
                                                {{-- Unselected --}}
                                                <div>
                                                    <p class="text-xs uppercase tracking-wide text-ink-500 dark:text-ink-300 mb-2">Available</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        <template x-for="sub in (subMap[m.id] || [])" :key="sub.id">
                                                            <button type="button"
                                                                    x-show="!isSelected(sub.id)"
                                                                    @click="toggleSub(sub.id)"
                                                                    class="px-3 py-1.5 rounded-full border border-sand-300 dark:border-ink-700 text-ink-700 dark:text-ink-200 hover:border-copper hover:text-copper transition text-sm">
                                                                <span x-text="sub.name"></span>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>

                                                {{-- Selected --}}
                                                <div>
                                                    <p class="text-xs uppercase tracking-wide text-ink-500 dark:text-ink-300 mb-2">Selected</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        <template x-for="sub in (subMap[m.id] || [])" :key="'sel-'+sub.id">
                                                            <div x-show="isSelected(sub.id)"
                                                                 class="inline-flex items-center pl-3 pr-2 py-1.5 rounded-full bg-copper-light text-copper-dark text-sm">
                                                                <span x-text="sub.name"></span>
                                                                <button type="button" @click="toggleSub(sub.id)" class="ml-2 rounded-full hover:text-copper">&times;</button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Hidden inputs for submission --}}
                                <template x-for="id in selectedSubs" :key="'hidden-'+id">
                                    <input type="hidden" name="activities[]" :value="id">
                                </template>

                                {{-- Global Selected Summary --}}
                                <div class="mt-4 rounded-2xl border border-sand-200 dark:border-ink-700 bg-sand-50 dark:bg-sand-800/40 p-4">
                                    <p class="text-sm font-medium text-ink-700 dark:text-ink-200 mb-2">Your selected interests:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-if="selectedSubs.length === 0">
                                            <span class="text-sm text-ink-500 dark:text-ink-300">None selected yet.</span>
                                        </template>
                                        <template x-for="id in selectedSubs" :key="'summary-'+id">
                                            <div class="inline-flex items-center pl-3 pr-2 py-1.5 rounded-full bg-copper-light text-copper-dark text-sm">
                                                <span x-text="subName(id)"></span>
                                                <button type="button" @click="toggleSub(id)" class="ml-2 rounded-full hover:text-copper">&times;</button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ========= Budget (Multi-select, Ordered, Default Moderate) ========= --}}
                        <div x-cloak x-show="activeTab === 'budget'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">

                            <h3 class="text-lg font-semibold text-copper mb-3">Budget Preferences</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-200/70 mb-3">
                                Select all price levels that you are comfortable with.
                            </p>

                            @php
                                $priceMain = $mainOptions->firstWhere('name', 'Budget & Price Level');
                                $priceSubs = $priceMain ? ($subMap[$priceMain->id] ?? []) : [];

                                // Desired left-to-right order:
                                $desiredOrder = [
                                    'Free or Low Cost' => 0,
                                    'Budget-Friendly'  => 1,
                                    'Moderate'         => 2,
                                    'Luxury'           => 3,
                                ];

                                // Normalize and sort the subs by desired order, keeping unknowns at end
                                $priceSubs = collect($priceSubs)
                                    ->sortBy(function($s) use ($desiredOrder) {
                                        $name = trim($s['name']);
                                        return $desiredOrder[$name] ?? 999;
                                    })
                                    ->values()
                                    ->all();

                                // Build selected values; default to 'Moderate' when empty
                                $selectedBudgetValues = old('budget', $preferences->where('key', 'budget')->pluck('value')->toArray() ?? []);
                                if (empty($selectedBudgetValues)) {
                                    $selectedBudgetValues = ['Moderate'];
                                }
                            @endphp

                            <div class="flex flex-wrap gap-2">
                                @foreach ($priceSubs as $sub)
                                    @php
                                        $value = trim($sub['name']);
                                        $id = 'budget_' . \Illuminate\Support\Str::slug($value) . '_' . $loop->index;
                                    @endphp

                                    <div class="inline-block">
                                        <input
                                            id="{{ $id }}"
                                            type="checkbox"
                                            name="budget[]"
                                            value="{{ $value }}"
                                            class="peer hidden"
                                            @checked(in_array($value, $selectedBudgetValues))
                                        >
                                        <label for="{{ $id }}"
                                            class="inline-flex items-center px-4 py-1.5 rounded-full border border-copper text-copper 
                                                    hover:bg-copper hover:text-white text-sm font-medium cursor-pointer shadow-sm
                                                    transition-all duration-150 ease-out
                                                    peer-checked:bg-gradient-copper peer-checked:text-white peer-checked:shadow-glow peer-checked:scale-[1.05]">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- ========= Dietary ========= --}}
                        <div x-cloak x-show="activeTab === 'dietary'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            <h3 class="text-lg font-semibold text-copper mb-3">Dietary Preferences</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-200/70 mb-3">Select all dietary preferences that apply.</p>

                            <div class="flex flex-wrap gap-2">
                                @foreach ($dietaryOptions as $diet)
                                    @php $id = 'diet_'.\Illuminate\Support\Str::slug($diet).'_'.$loop->index; @endphp
                                    <div class="inline-block">
                                        <input
                                            id="{{ $id }}"
                                            type="checkbox"
                                            name="dietary[]"
                                            value="{{ $diet }}"
                                            class="peer hidden"
                                            @checked(in_array($diet, old('dietary', $preferences->where('key', 'dietary')->pluck('value')->toArray() ?? [])))
                                        >
                                        <label for="{{ $id }}"
                                            class="inline-flex items-center px-4 py-1.5 rounded-full border border-copper text-copper 
                                                    hover:bg-copper hover:text-white text-sm font-medium cursor-pointer shadow-sm
                                                    transition-all duration-150 ease-out
                                                    peer-checked:bg-gradient-copper peer-checked:text-white peer-checked:shadow-glow peer-checked:scale-[1.05]">
                                            {{ $diet }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- ========= Cuisine (Select/Deselect All) ========= --}}
                        <div x-cloak x-show="activeTab === 'cuisine'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            <h3 class="text-lg font-semibold text-copper mb-3">Cuisine Preferences</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-200/70 mb-3">Select the cuisines you enjoy most.</p>

                            @php
                                $preCuisines = old('cuisine', $preferences->where('key', 'cuisine')->pluck('value')->toArray() ?? []);
                            @endphp

                            <div
                              x-data="{
                                  all: @js($cuisineOptions),
                                  selected: @js($preCuisines),
                                  isSelected(v){ return this.selected.includes(v) },
                                  selectAll(){ this.selected = [...this.all] },
                                  clearAll(){ this.selected = [] }
                              }"
                              class="space-y-3"
                            >
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            @click="selectAll()"
                                            class="px-3 py-1.5 rounded-full text-sm font-medium border border-copper text-copper hover:bg-copper hover:text-white transition">
                                        Select all
                                    </button>
                                    <button type="button"
                                            @click="clearAll()"
                                            class="px-3 py-1.5 rounded-full text-sm font-medium border border-ink-400 text-ink-700 dark:text-ink-200 hover:border-copper hover:text-copper transition">
                                        Clear all
                                    </button>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(cuisine, idx) in all" :key="'cuisine-'+idx">
                                        <div class="inline-block">
                                            <input
                                                :id="`cuisine_${idx}`"
                                                type="checkbox"
                                                name="cuisine[]"
                                                :value="cuisine"
                                                class="peer hidden"
                                                :checked="isSelected(cuisine)"
                                                @change="($event.target.checked) 
                                                         ? (!selected.includes(cuisine) && selected.push(cuisine)) 
                                                         : (selected = selected.filter(v => v !== cuisine))"
                                            >
                                            <label :for="`cuisine_${idx}`"
                                                class="inline-flex items-center px-4 py-1.5 rounded-full border border-copper text-copper 
                                                       hover:bg-copper hover:text-white text-sm font-medium cursor-pointer shadow-sm
                                                       transition-all duration-150 ease-out
                                                       peer-checked:bg-gradient-copper peer-checked:text-white peer-checked:shadow-glow peer-checked:scale-[1.05]">
                                                <span x-text="cuisine"></span>
                                            </label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                    {{-- === Buttons === --}}
                    <div class="mt-10 flex justify-end gap-4">
                        <a href="{{ route('traveler.preference-profiles.index') }}"
                           class="group px-6 py-2.5 rounded-full border border-ink-500 dark:border-ink-700
                                  text-ink-700 dark:text-ink-200 hover:text-copper hover:border-copper
                                  hover:scale-[1.03] hover:shadow-glow transition-all duration-200 ease-out
                                  font-medium shadow-soft dark:shadow-glow-dark">
                            Cancel
                        </a>

                        <button type="submit"
                                :disabled="isSubmitting"
                                class="group flex items-center justify-center gap-2 px-8 py-2.5 rounded-full
                                       bg-gradient-copper dark:bg-gradient-copper-dark text-white font-semibold
                                       shadow-soft hover:shadow-glow hover:scale-[1.03]
                                       transition-all duration-200 ease-out disabled:opacity-80 disabled:cursor-not-allowed">

                            <template x-if="!isSubmitting">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-200 group-hover:rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>

                            <template x-if="isSubmitting">
                                <svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"/>
                                </svg>
                            </template>

                            <span x-text="isSubmitting ? 'Saving...' : '{{ isset($preferenceProfile) ? 'Save Changes' : 'Create Profile' }}'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Toast Notification --}}
        <div x-show="showToast"
             x-transition.opacity.duration.500ms
             class="fixed bottom-6 right-6 bg-gradient-copper dark:bg-gradient-copper-dark
                    text-white px-5 py-3 rounded-xl shadow-glow text-sm font-medium
                    flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>Profile saved successfully!</span>
        </div>
    </div>
</x-app-layout>
