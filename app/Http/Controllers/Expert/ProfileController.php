<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Services\CityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->expert) {
            $user->expert()->create([
                'city' => '',
                'bio' => '',
            ]);
        }

        $expert = $user->expert;
        $cities = CityHelper::all();

        return view('expert.profile.edit', compact('expert', 'cities'));
    }

    public function update(Request $request)
    {
        $validCities = CityHelper::all()->toArray();

        $data = $request->validate([
            'city' => ['required', 'string', 'in:' . implode(',', $validCities)],
            'bio' => 'nullable|string',
            'photo_url' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->expert->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->expert) {
            $user->expert()->create([
                'city' => '',
                'bio' => '',
            ]);
        }

        $expert = $user->expert;

        return view('expert.profile.show', compact('expert'));
    }
}
