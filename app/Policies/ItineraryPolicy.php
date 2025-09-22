<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;

class ItineraryPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Admins can do anything.
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'traveler'], true);
    }

    public function view(User $user, Itinerary $itinerary): bool
    {
        return $user->traveler && $itinerary->traveler_id === $user->traveler->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'traveler'], true) && (bool) $user->traveler;
    }

    public function update(User $user, Itinerary $itinerary): bool
    {
        return $this->view($user, $itinerary);
    }

    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $this->view($user, $itinerary);
    }
}
