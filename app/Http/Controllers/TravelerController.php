<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelerController extends Controller
{
    public function index()
    {
        return view('traveler.dashboard');
    }
}
