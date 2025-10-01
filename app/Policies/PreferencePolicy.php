<?php

namespace App\Policies;

use App\Models\Preference;
use App\Models\User;

class PreferencePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        // Travelers with a traveler record can see their preferences list
        return $user->role === 'traveler' && (bool) $user->traveler;
    }

    public function view(User $user, Preference $pref): bool
    {
        // If preferences belong to a profile which belongs to a traveler:
        $travelerId = $user->traveler?->id;
        $ownerId = $pref->profile?->traveler_id     // common case
            ?? $pref->traveler_id                              // fallback if stored directly
            ?? null;

        return $user->role === 'traveler'
            && $travelerId !== null
            && $ownerId !== null
            && $travelerId === $ownerId;
    }

    public function create(User $user): bool
    {
        return $user->role === 'traveler' && (bool) $user->traveler;
    }

    public function update(User $user, Preference $pref): bool
    {
        return $this->view($user, $pref);
    }

    public function delete(User $user, Preference $pref): bool
    {
        return $this->view($user, $pref);
    }
}
