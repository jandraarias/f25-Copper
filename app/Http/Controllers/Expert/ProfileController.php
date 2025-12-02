<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        // Later: pass Expert data to view
        return view('expert.profile.edit');
    }

    public function update(Request $request)
    {
        // Later: validate and update expert profile
    }
}
