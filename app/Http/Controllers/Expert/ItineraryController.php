<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    public function index()
    {
        // Later: fetch itineraries assigned to this expert
        return view('expert.itineraries.index');
    }
}
