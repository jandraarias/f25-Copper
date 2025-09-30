<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ItineraryPdfController extends Controller
{
    public function __invoke(Request $request, Itinerary $itinerary)
    {
        $this->authorize('view', $itinerary); // traveler policy gate

        $pdf = Pdf::loadView('pdf.itinerary', ['itinerary' => $itinerary->load('items')]);
        $filename = 'Itinerary - ' . str($itinerary->name)->slug() . '.pdf';

        return $pdf->download($filename);
    }
}
