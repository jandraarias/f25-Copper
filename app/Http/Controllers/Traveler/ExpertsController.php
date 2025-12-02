<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expert;

class ExpertsController extends Controller
{
    /**
     * Display a searchable, filterable list of local experts.
     */
    public function index(Request $request)
    {
        // === Inputs ===
        $q       = trim($request->get('q', '')) ?: null;
        $sort    = $request->get('sort', 'popularity');
        $cities  = array_filter((array) $request->get('cities', []));

        // All cities for filter UI
        $allCities = Expert::select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->toArray();

        // === Build Query ===
        $query = Expert::query()
            ->withCount('reviews') // ->reviews_count
            ->with('user')         // preload associated user
            ->when(!empty($cities), fn($q2) => $q2->whereIn('city', $cities))
            ->when($q, function ($q2) use ($q) {
                $q2->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                          ->orWhere('city', 'like', "%{$q}%")
                          ->orWhere('bio', 'like', "%{$q}%");
                });
            });

        // === Sorting ===
        match ($sort) {
            'alphabetical' => $query->orderBy('name'),
            'newest'       => $query->orderBy('created_at', 'desc'),
            default        => $query->orderBy('reviews_count', 'desc'),
        };

        // === Final Paginated Results ===
        $experts = $query->paginate(12)->withQueryString();

        return view('traveler.experts.index', [
            'experts'   => $experts,
            'allCities' => $allCities,
            'cities'    => $cities,
            'q'         => $q,
            'sort'      => $sort,
        ]);
    }

    /**
     * Display a PUBLIC traveler-facing expert profile.
     * Travelers are allowed to VIEW an expert, but not edit them.
     */
    public function show(Expert $expert)
    {
        // Eager loading: ensures no N+1 queries
        $expert->load([
            'user',
            'reviews.user',  // reviewer info
        ]);

        return view('traveler.experts.show', [
            'expert' => $expert,
        ]);
    }
}
