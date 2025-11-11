<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{
    /**
     * Display a single place detail page.
     */
    public function show(Place $place, Request $request)
    {
        $user = Auth::user();

        // Traveler itineraries
        $itineraries = collect();
        if ($user && $user->role === 'traveler' && $user->traveler) {
            $itineraries = $user->traveler
                ->itineraries()
                ->orderBy('created_at', 'desc')
                ->get(['id', 'name']);
        }

        // Pagination allowed: 5, 10, 25, 50, 100
        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 25, 50, 100]) ? $perPage : 10;

        /**
         * Fetch ALL reviews (small dataset)
         * Deduplicate BEFORE pagination
         * Then manually paginate the unique collection
         */
        $allReviews = $place->reviews()
            ->orderByDesc('published_at_date')
            ->get()
            ->unique(fn($r) => $r->author . '|' . $r->content)
            ->values();

        // Manual pagination on the unique reviews
        $currentPage = $request->query('page', 1);
        $pagedReviews = $allReviews->slice(($currentPage - 1) * $perPage, $perPage);

        // Convert sliced results into a LengthAwarePaginator
        $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedReviews,
            $allReviews->count(),       // Total unique reviews
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('places.show', [
            'place'       => $place,
            'itineraries' => $itineraries,
            'reviews'     => $reviews,
        ]);
    }

    public function reviewsPage(Place $place, $page)
    {
        $perPage = 10;

        $reviews = $place->reviews()
            ->orderBy('published_at_date', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return response()->json([
            'reviews' => view('reviews._review-card-list', [
                'reviews' => $reviews
            ])->render(),
            'hasMore' => $place->reviews()->count() > $page * $perPage,
        ]);
    }

    /**
     * Normalize various hours formats into a consistent array.
     * Supports:
     * - Google-style objects
     * - Arrays
     * - Strings like “Mon-Fri: 9am–5pm”
     */
    protected function parseHours($raw)
    {
        if (is_array($raw)) {
            return $raw; // Already structured
        }

        if (is_string($raw)) {
            // Split multiple lines
            $lines = preg_split('/\r\n|\r|\n/', $raw);
            $result = [];

            foreach ($lines as $line) {
                if (str_contains($line, ':')) {
                    [$day, $hours] = explode(':', $line, 2);
                    $result[trim($day)] = trim($hours);
                }
            }

            return $result ?: null;
        }

        return null;
    }
}
