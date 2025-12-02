<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $business = Auth::user()->business;

        if (!$business) {
            $business = Auth::user()->business()->create([
                'name' => Auth::user()->name,
                'city' => '',
                'description' => '',
            ]);
        }

        return view('business.profile.edit', compact('business'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'city'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'website'     => 'nullable|string|max:255',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $business = Auth::user()->business;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('business_photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $business->update($data);

        return redirect()
            ->route('business.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
