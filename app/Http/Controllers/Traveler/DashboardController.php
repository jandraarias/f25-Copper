<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Keep it simple for now; you can pass stats later if you want.
        return view('traveler.dashboard');
    }
}
