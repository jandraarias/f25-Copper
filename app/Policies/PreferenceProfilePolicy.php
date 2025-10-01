<?php

namespace App\Policies;

use App\Models\PreferenceProfile;
use App\Models\User;

class PreferenceProfilePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'traveler' && (bool) $user->traveler;
    }

    public function view(User $user, PreferenceProfile $profile): bool
    {
        $travelerId = $user->traveler?->id;
        return $user->role === 'traveler'
            && $travelerId !== null
            && $travelerId === $profile->traveler_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'traveler' && (bool) $user->traveler;
    }

    public function update(User $user, PreferenceProfile $profile): bool
    {
        return $this->view($user, $profile);
    }

    public function delete(User $user, PreferenceProfile $profile): bool
    {
        return $this->view($user, $profile);
    }
}
