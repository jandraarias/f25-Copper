<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\ExpertSuggestion;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\Place;
use App\Models\PlaceSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryEditController extends Controller
{
    /**
     * Show the edit view for an itinerary (approved by expert).
     */
    public function edit(Itinerary $itinerary)
    {
        $expert = Auth::user()->expert;
        if (!$expert) {
            abort(403);
        }

        // Ensure the itinerary is assigned to this expert (accepted invite)
        $assigned = $itinerary->expertInvitations()
            ->where('expert_id', $expert->id)
            ->where('status', 'accepted')
            ->exists();

        if (!$assigned) {
            abort(403);
        }

        $itinerary->load([
            'items.place',
            'items.expertSuggestions.place',
            'countries',
            'traveler.user'
        ]);

        return view('expert.itineraries.edit', compact('itinerary'));
    }

    /**
     * Search for existing places to suggest as replacements.
     */
    public function searchPlaces(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'type' => 'nullable|string',
        ]);

        $expert = Auth::user()->expert;
        if (!$expert) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $query = $request->input('query');
        $type = $request->input('type');

        try {
            $searchTerm = "%{$query}%";
            
            $places = Place::where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm)
                  ->orWhere('address', 'LIKE', $searchTerm);
            });

            if ($type && !empty(trim($type))) {
                $places->where('categories', 'LIKE', "%{$type}%");
            }

            $results = $places->orderBy('name')
                ->limit(10)
                ->get([
                    'id',
                    'name',
                    'address',
                    'rating',
                    'num_reviews',
                    'categories',
                    'image',
                ]);

            return response()->json([
                'ok' => true,
                'results' => $results->toArray(),
                'count' => $results->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Place search error', [
                'message' => $e->getMessage(),
                'query' => $query,
            ]);
            return response()->json([
                'ok' => false,
                'error' => 'Search failed',
            ], 500);
        }
    }

    /**
     * Submit a suggestion to replace an itinerary item.
     */
    public function suggestReplacement(Request $request, Itinerary $itinerary)
    {
        $expert = Auth::user()->expert;
        if (!$expert) {
            abort(403);
        }

        // Verify expert has access to this itinerary
        $assigned = $itinerary->expertInvitations()
            ->where('expert_id', $expert->id)
            ->where('status', 'accepted')
            ->exists();

        if (!$assigned) {
            abort(403);
        }

        // Get the item from query parameter
        $itemId = $request->input('item_id');
        $item = ItineraryItem::find($itemId);

        if (!$item || $item->itinerary_id !== $itinerary->id) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid itinerary item',
            ], 400);
        }

        $request->validate([
            'place_id' => 'required_without:new_place|integer|exists:places,id',
            'new_place' => 'required_without:place_id|array',
            'new_place.name' => 'required_if:new_place,required|string|max:255',
            'new_place.description' => 'nullable|string',
            'new_place.location' => 'nullable|string',
            'new_place.type' => 'nullable|string',
            'new_place.rating' => 'nullable|numeric|min:0|max:5',
            'new_place.phone' => 'nullable|string',
            'new_place.website' => 'nullable|url',
            'reason' => 'nullable|string|max:500',
        ]);

        if ($request->has('place_id') && $request->input('place_id')) {
            // Existing place replacement
            $suggestion = ExpertSuggestion::create([
                'itinerary_item_id' => $item->id,
                'expert_id' => $expert->id,
                'place_id' => $request->input('place_id'),
                'type' => 'replacement',
                'status' => 'pending',
                'reason' => $request->input('reason'),
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Suggestion submitted for approval',
                'suggestion_id' => $suggestion->id,
            ]);
        }

        if ($request->has('new_place') && $request->input('new_place')) {
            // New place suggestion
            $newPlaceData = $request->input('new_place');

            $suggestion = ExpertSuggestion::create([
                'itinerary_item_id' => $item->id,
                'expert_id' => $expert->id,
                'type' => 'new_place',
                'status' => 'pending',
                'reason' => $request->input('reason'),
            ]);

            PlaceSuggestion::create([
                'expert_suggestion_id' => $suggestion->id,
                'expert_id' => $expert->id,
                'name' => $newPlaceData['name'],
                'description' => $newPlaceData['description'] ?? null,
                'location' => $newPlaceData['location'] ?? null,
                'type' => $newPlaceData['type'] ?? null,
                'rating' => $newPlaceData['rating'] ?? null,
                'phone' => $newPlaceData['phone'] ?? null,
                'website' => $newPlaceData['website'] ?? null,
                'status' => 'pending',
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'New place suggestion submitted for approval',
                'suggestion_id' => $suggestion->id,
            ]);
        }

        return response()->json([
            'ok' => false,
            'message' => 'Invalid request',
        ], 400);
    }

    /**
     * Get suggestions for an itinerary item.
     */
    public function getItemSuggestions(ItineraryItem $item)
    {
        $expert = Auth::user()->expert;
        if (!$expert) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Verify expert has access
        $assigned = $item->itinerary->expertInvitations()
            ->where('expert_id', $expert->id)
            ->where('status', 'accepted')
            ->exists();

        if (!$assigned) {
            abort(403);
        }

        $suggestions = $item->expertSuggestions()
            ->with(['place', 'placeSuggestion'])
            ->get();

        return response()->json([
            'ok' => true,
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * Get traveler-facing view to approve/reject expert suggestions.
     */
    public function manageSuggestions(Itinerary $itinerary)
    {
        // Only traveler can manage suggestions
        if ($itinerary->traveler_id !== Auth::user()->traveler?->id) {
            abort(403);
        }

        $itinerary->load([
            'items.expertSuggestions.expert.user',
            'items.expertSuggestions.place',
            'items.expertSuggestions.placeSuggestion',
        ]);

        // Group suggestions by item
        $suggestionsByItem = $itinerary->items->mapWithKeys(function ($item) {
            return [
                $item->id => $item->expertSuggestions->groupBy('status'),
            ];
        });

        return view('traveler.itineraries.manage-suggestions', compact('itinerary', 'suggestionsByItem'));
    }

    /**
     * Approve an expert suggestion (traveler action).
     */
    public function approveSuggestion(Request $request, ExpertSuggestion $suggestion)
    {
        $itinerary = $suggestion->itineraryItem->itinerary;

        // Verify traveler owns this itinerary
        if ($itinerary->traveler_id !== Auth::user()->traveler?->id) {
            abort(403);
        }

        $suggestion->approve();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Suggestion approved',
                'suggestion_id' => $suggestion->id,
            ]);
        }

        return redirect()
            ->route('traveler.itineraries.suggestions.index', $itinerary)
            ->with('success', 'Suggestion approved successfully');
    }

    /**
     * Reject an expert suggestion (traveler action).
     */
    public function rejectSuggestion(Request $request, ExpertSuggestion $suggestion)
    {
        $itinerary = $suggestion->itineraryItem->itinerary;

        // Verify traveler owns this itinerary
        if ($itinerary->traveler_id !== Auth::user()->traveler?->id) {
            abort(403);
        }

        $suggestion->reject();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Suggestion rejected',
                'suggestion_id' => $suggestion->id,
            ]);
        }

        return redirect()
            ->route('traveler.itineraries.suggestions.index', $itinerary)
            ->with('success', 'Suggestion rejected');
    }
}
