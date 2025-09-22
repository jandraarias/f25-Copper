<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PublicItineraryController extends Controller
{
    public function show(Request $request, string $uuid): View|Response
    {
        $itinerary = Itinerary::with(['items'])
            ->where('public_uuid', $uuid)
            ->firstOrFail();

        return view('public.itinerary', compact('itinerary'));
    }
}