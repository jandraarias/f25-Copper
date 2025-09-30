<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $itinerary->name }} — Itinerary</title>
  <style>
    /* Keep CSS simple for DomPDF */
    * { font-family: DejaVu Sans, sans-serif; }
    body { margin: 24px; font-size: 12px; color: #111; }
    h1 { font-size: 20px; margin: 0 0 6px; }
    h2 { font-size: 14px; margin: 18px 0 8px; }
    .muted { color: #555; }
    .card { border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin-bottom: 10px; }
    .row { display: flex; gap: 8px; flex-wrap: wrap; }
    .tag { border: 1px solid #999; border-radius: 999px; padding: 2px 8px; font-size: 10px; }
    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-4 { margin-bottom: 16px; }
  </style>
</head>
<body>

  <h1>{{ $itinerary->name }}</h1>

  <div class="muted mb-4">
    @php
      $start = $itinerary->start_date ? \Illuminate\Support\Carbon::parse($itinerary->start_date) : null;
      $end   = $itinerary->end_date   ? \Illuminate\Support\Carbon::parse($itinerary->end_date)   : null;
    @endphp
    @if($itinerary->location) {{ $itinerary->location }} @endif
    @if($itinerary->country) • {{ $itinerary->country }} @endif
    <br>
    @if($start) {{ $start->toFormattedDateString() }} @endif
    @if($end) — {{ $end->toFormattedDateString() }} @endif
  </div>

  @if($itinerary->description)
    <div class="mb-4">{{ $itinerary->description }}</div>
  @endif

  <h2>Items</h2>
  @php
    $items = $itinerary->items ? $itinerary->items->sortBy('start_time') : collect();
  @endphp

  @forelse($items as $item)
    @php
      $s = $item->start_time ? \Illuminate\Support\Carbon::parse($item->start_time) : null;
      $e = $item->end_time   ? \Illuminate\Support\Carbon::parse($item->end_time)   : null;
    @endphp
    <div class="card">
      <div class="mb-2"><strong>{{ $item->title }}</strong></div>
      <div class="muted mb-2">
        @if($s) {{ $s->format('M j, Y g:i a') }} @endif
        @if($e) – {{ $e->format('M j, Y g:i a') }} @endif
        @if($item->location) • {{ $item->location }} @endif
      </div>
      @if($item->details)
        <div>{{ $item->details }}</div>
      @endif
    </div>
  @empty
    <div class="muted">No items yet.</div>
  @endforelse

</body>
</html>
