<?php

namespace App\Http\Controllers;

use App\Models\BusinessOffer;
use Illuminate\Http\Request;

class BusinessOfferController extends Controller
{
    // Show all business offers
    public function index()
    {
        $offers = BusinessOffer::all();
        return response()->json($offers);
    }

    // Store a new business offer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:120',
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'active' => 'boolean',
        ]);

        $offer = BusinessOffer::create($validated);

        return response()->json([
            'message' => 'Business offer created successfully',
            'data' => $offer
        ]);
    }

    // Show a single offer
    public function show(BusinessOffer $businessOffer)
    {
        return response()->json($businessOffer);
    }

    // Update an existing offer
    public function update(Request $request, BusinessOffer $businessOffer)
    {
        $validated = $request->validate([
            'title' => 'string|max:120',
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'active' => 'boolean',
        ]);

        $businessOffer->update($validated);

        return response()->json([
            'message' => 'Business offer updated successfully',
            'data' => $businessOffer
        ]);
    }

    // Delete an offer
    public function destroy(BusinessOffer $businessOffer)
    {
        $businessOffer->delete();
        return response()->json(['message' => 'Business offer deleted']);
    }
}