<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;

class ItineraryPolicy
{
    /**
     * Admin override: allow all abilities.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        // Travelers can view their own list (controller queries by auth()->id()).
        // Other roles can be tightened via middleware; we keep this permissive.
        return in_array($user->role, ['traveler', 'expert', 'business', 'admin'], true);
    }

    public function view(User $user, Itinerary $itinerary): bool
    {
        return $itinerary->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Traveler creates their own itineraries (your routes already require role:traveler).
        return $user->role === 'traveler';
    }

    public function update(User $user, Itinerary $itinerary): bool
    {
        return $itinerary->user_id === $user->id;
    }

    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $itinerary->user_id === $user->id;
    }
}
