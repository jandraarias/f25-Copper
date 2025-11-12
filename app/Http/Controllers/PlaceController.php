<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

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

        // Capture filter inputs
        $sort   = $request->query('sort', 'newest');   // newest | oldest | highest | lowest
        $rating = $request->query('rating');           // 5 | 4 | 3 | null
        $photos = $request->query('photos');           // 1 | null

        // Pagination allowed: 5–100
        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 25, 50, 100]) ? $perPage : 10;

        /**
         * ✅ Fetch ALL reviews — full dataset
         */
        $allReviews = $place->reviews()
            ->orderByDesc('published_at_date')
            ->get();

        /**
         * ✅ Apply filters (rating / photos)
         */
        if ($rating) {
            $allReviews = $allReviews->filter(fn($r) => $r->rating >= (int)$rating);
        }

        if ($photos) {
            $allReviews = $allReviews->filter(fn($r) => $r->has_photos);
        }

        /**
         * ✅ Apply sorting (all four modes)
         */
        switch ($sort) {
            case 'oldest':
                $allReviews = $allReviews->sortBy('published_at_date')->values();
                break;

            case 'highest':
                $allReviews = $allReviews->sortByDesc('rating')->values();
                break;

            case 'lowest':
                $allReviews = $allReviews->sortBy('rating')->values();
                break;

            case 'newest':
            default:
                $allReviews = $allReviews->sortByDesc('published_at_date')->values();
                break;
        }

        /**
         * ✅ Deduplicate reviews AFTER sorting
         */
        $allReviews = $allReviews
            ->unique(fn($r) => $r->author . '|' . $r->content)
            ->values();

        /**
         * ✅ Manual pagination
         */
        $currentPage = $request->query('page', 1);

        $pagedReviews = $allReviews
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $reviews = new LengthAwarePaginator(
            $pagedReviews,
            $allReviews->count(),
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

    /**
     * AJAX infinite-scroll / load-more endpoint
     */
    public function reviewsPage(Place $place, $page)
    {
        $perPage = 10;

        $sort   = request()->query('sort', 'newest');
        $rating = request()->query('rating');
        $photos = request()->query('photos');

        // Base dataset
        $allReviews = $place->reviews()
            ->orderByDesc('published_at_date')
            ->get();

        // Filters
        if ($rating) {
            $allReviews = $allReviews->filter(fn($r) => $r->rating >= (int)$rating);
        }
        if ($photos) {
            $allReviews = $allReviews->filter(fn($r) => $r->has_photos);
        }

        // Sorting
        switch ($sort) {
            case 'oldest':
                $allReviews = $allReviews->sortBy('published_at_date')->values();
                break;

            case 'highest':
                $allReviews = $allReviews->sortByDesc('rating')->values();
                break;

            case 'lowest':
                $allReviews = $allReviews->sortBy('rating')->values();
                break;

            case 'newest':
            default:
                $allReviews = $allReviews->sortByDesc('published_at_date')->values();
                break;
        }

        // Deduplicate
        $allReviews = $allReviews
            ->unique(fn($r) => $r->author . '|' . $r->content)
            ->values();

        // Pagination slice
        $total = $allReviews->count();

        $pagedReviews = $allReviews
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        return response()->json([
            'reviews' => view('reviews._review-card-list', [
                'reviews' => $pagedReviews
            ])->render(),
            'hasMore' => $page * $perPage < $total,
        ]);
    }

    /**
     * Hours parsing helper
     */
    protected function parseHours($raw)
    {
        if (is_array($raw)) {
            return $raw;
        }

        if (is_string($raw)) {
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
