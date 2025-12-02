<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Services\CityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $expert = Auth::user()->expert;

        $data = $request->validate([
            'bio'              => 'nullable|string|max:1000',
            'expertise'        => 'nullable|string|max:255',
            'languages'        => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'city'             => ['required', 'string'],
            'photo'            => 'nullable|image|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('expert_photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $expert->update($data);

        return redirect()
            ->route('expert.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    public function edit()
    {
        $user = Auth::user();
        $cities = CityHelper::all();

        if (!$user->expert) {
            $user->expert()->create([
                'name' => $user->name,
                'city' => $cities->first(),
                'bio'  => '',
            ]);
        }

        return view('expert.profile.edit', [
            'expert' => $user->expert,
            'cities' => $cities,
        ]);
    }

    public function show()
    {
        $user = Auth::user();

        if (!$user->expert) {
            $user->expert()->create([
                'name' => $user->name,
                'city' => CityHelper::all()->first(),
                'bio'  => '',
            ]);
        }

        return view('expert.profile.show', [
            'expert' => $user->expert,
        ]);
    }
}
