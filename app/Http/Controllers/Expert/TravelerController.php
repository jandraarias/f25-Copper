<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Traveler;

class TravelerController extends Controller
{
    public function index()
    {
        $travelers = Traveler::with('user')
            ->orderBy(
                \App\Models\User::select('name')
                    ->whereColumn('users.id', 'travelers.user_id')
            )
            ->get();

        return view('expert.travelers.index', compact('travelers'));
    }

    public function show(Traveler $traveler)
    {
        return view('expert.travelers.show', compact('traveler'));
    }
}
