<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TravelerController extends Controller
{
    public function index()
    {
        // Later: fetch travelers needing expert help
        return view('expert.travelers.index');
    }
}
