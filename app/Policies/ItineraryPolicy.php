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
        // Accept either schema: direct user_id OR via traveler->user_id
        if (isset($itinerary->user_id) && $itinerary->user_id === $user->id) {
            return true;
        }

        return $itinerary->traveler && $itinerary->traveler->user_id === $user->id;
    }

    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $this->update($user, $itinerary);
    }

    public function view(User $user, Itinerary $itinerary): bool
    {
        return $this->update($user, $itinerary);
    }

    public function create(User $user): bool
    {
        return $user->role === 'traveler';
    }
}
