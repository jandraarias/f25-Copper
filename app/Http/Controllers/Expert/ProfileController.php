<?php

// app/Http/Controllers/Expert/ProfileController.php
namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Services\CityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validCities = CityHelper::all()->toArray();

        $data = $request->validate([
            'bio'              => 'nullable|string',
            'expertise'        => 'nullable|string|max:255',
            'languages'        => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:60',
            //'city'             => ['required', 'string', 'in:' . implode(',', $validCities)],
            'city'             => ['required', 'string'], // <-- or keep strict validation if desired
            'photo'            => 'nullable|image|max:2048',
        ]);

        $expert = Auth::user()->expert;

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('expert_photos', 'public');
            $data['photo_url'] = '/storage/' . $path;
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
                'bio' => '',
            ]);
        }

        $expert = $user->expert;

        return view('expert.profile.edit', compact('expert', 'cities'));
    }

    public function show()
    {
        $user = Auth::user();
        $cities = CityHelper::all();

        if (!$user->expert) {
            $user->expert()->create([
                'name' => $user->name,
                'city' => $cities->first(),
                'bio' => '',
            ]);
        }

        $expert = $user->expert;

        return view('expert.profile.show', compact('expert'));
    }

}
