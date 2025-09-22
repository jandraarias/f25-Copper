<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Preference;

class PreferencePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'traveler'], true);
    }

    public function view(User $user, Preference $pref): bool
    {
        $travelerId = $user->traveler?->id;
        return $travelerId && $pref->preferenceProfile?->traveler_id === $travelerId;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'traveler'], true) && (bool) $user->traveler;
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
