<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;

class ItineraryPolicy
{
    /**
     * Grant all abilities to admins.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    /**
     * Determine if the user can view any itineraries.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'traveler' && $user->traveler !== null;
    }

    /**
     * Determine if the user can view a specific itinerary.
     *
     * Owners and collaborators can view.
     */
    public function view(User $user, Itinerary $itinerary): bool
    {
        $travelerId = $user->traveler?->id;

        return $user->role === 'traveler'
            && (
                $travelerId === $itinerary->traveler_id ||
                $itinerary->collaborators()->where('user_id', $user->id)->exists()
            );
    }

    /**
     * Determine if the user can create itineraries.
     */
    public function create(User $user): bool
    {
        return $user->role === 'traveler' && $user->traveler !== null;
    }

    /**
     * Determine if the user can update a specific itinerary.
     *
     * Owners and collaborators can update.
     */
    public function update(User $user, Itinerary $itinerary): bool
    {
        $travelerId = $user->traveler?->id;

        return $user->role === 'traveler'
            && (
                $travelerId === $itinerary->traveler_id ||
                $itinerary->collaborators()->where('user_id', $user->id)->exists()
            );
    }

    /**
     * Determine if the user can delete the itinerary.
     *
     * Only the owner (not collaborators) can delete.
     */
    public function delete(User $user, Itinerary $itinerary): bool
    {
        $travelerId = $user->traveler?->id;

        return $user->role === 'traveler'
            && $travelerId !== null
            && $travelerId === $itinerary->traveler_id;
    }
}
