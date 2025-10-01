<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;

class ItineraryPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function update(User $user, Itinerary $itinerary): bool
    {
        $travelerId = $user->traveler?->id;
        return $user->role === 'traveler'
            && $travelerId !== null
            && $travelerId === $itinerary->traveler_id;
    }

    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $this->update($user, $itinerary);
    }

    public function view(User $user, Itinerary $itinerary): bool
    {
        return $this->update($user, $itinerary);
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'traveler' && (bool) $user->traveler;
    }

    public function create(User $user): bool
    {
        return $user->role === 'traveler' && (bool) $user->traveler;
    }
}
