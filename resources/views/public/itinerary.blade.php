<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>{{ $itinerary->name }} — Itinerary</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-2">{{ $itinerary->name }}</h1>
    <p class="text-sm text-gray-600 mb-4">
      {{ $itinerary->location }} {{ $itinerary->country ? '• '.$itinerary->country : '' }}<br>
      {{ \Illuminate\Support\Carbon::parse($itinerary->start_date)->toFormattedDateString() }}
      —
      {{ \Illuminate\Support\Carbon::parse($itinerary->end_date)->toFormattedDateString() }}
    </p>

    <div class="space-y-4">
      @forelse ($itinerary->items as $item)
        <div class="bg-white shadow rounded-xl p-4">
          <div class="font-medium">{{ $item->title }}</div>
          <div class="text-sm text-gray-600">
            {{ optional($item->start_time)->format('M j, g:i a') }}
            @if($item->end_time) – {{ $item->end_time->format('M j, g:i a') }} @endif
            @if($item->location) • {{ $item->location }} @endif
          </div>
          @if($item->details)
            <div class="mt-2 text-sm">{{ $item->details }}</div>
          @endif
        </div>
      @empty
        <p class="text-gray-600">No items yet.</p>
      @endforelse
    </div>
  </div>
</body>
</html>
