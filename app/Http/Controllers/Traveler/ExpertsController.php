<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expert;

class ExpertsController extends Controller
{
    public function index(Request $request)
    {
        // Inputs
        $q       = trim($request->get('q', '')) ?: null;
        $sort    = $request->get('sort', 'popularity');
        $cities  = array_filter((array) $request->get('cities', [])); // multi-select (OR)

        // For filters UI
        $allCities = Expert::select('city')->distinct()->orderBy('city')->pluck('city')->toArray();

        // Base query with popularity support
        $query = Expert::query()
            ->withCount('reviews') // ->reviews_count
            ->when(!empty($cities), fn($q2) => $q2->whereIn('city', $cities))
            ->when($q, function ($q2) use ($q) {
                $q2->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                          ->orWhere('city', 'like', "%{$q}%")
                          ->orWhere('bio', 'like', "%{$q}%");
                    // (Future) add specialization joins/JSON search here
                });
            });

        // Sorting
        switch ($sort) {
            case 'alphabetical':
                $query->orderBy('name');
                break;

            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;

            case 'popularity':
            default:
                $query->orderBy('reviews_count', 'desc');
                break;
        }

        // Paginate and keep query string for controls
        $experts = $query->paginate(12)->withQueryString();

        return view('traveler.experts.index', [
            'experts'   => $experts,
            'allCities' => $allCities,
            'cities'    => $cities,
            'q'         => $q,
            'sort'      => $sort,
        ]);
    }
}
