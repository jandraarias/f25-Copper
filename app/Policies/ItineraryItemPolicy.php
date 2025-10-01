<?php

namespace App\Policies;

use App\Models\ItineraryItem;
use App\Models\User;

class ItineraryItemPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'traveler' && (bool) $user->traveler;
    }

    public function view(User $user, ItineraryItem $item): bool
    {
        $travelerId = $user->traveler?->id;
        $ownerId = $item->itinerary?->traveler_id;

        return $user->role === 'traveler'
            && $travelerId !== null
            && $ownerId !== null
            && $travelerId === $ownerId;
    }

    public function create(User $user): bool
    {
        // Creation is usually checked against the parent itinerary in controllers,
        // but this keeps it consistent for simple cases.
        return $user->role === 'traveler' && (bool) $user->traveler;
    }

    public function update(User $user, ItineraryItem $item): bool
    {
        return $this->view($user, $item);
    }

    public function delete(User $user, ItineraryItem $item): bool
    {
        return $this->view($user, $item);
    }
}
