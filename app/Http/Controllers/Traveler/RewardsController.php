<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RewardsController extends Controller
{
    public function index(){
        // Get all rewards with the related place info
        $rewards = Reward::with('place')->get();

        // Pass rewards to a view
        return view('traveler.rewards.index', compact('rewards'));
    }
    
}
