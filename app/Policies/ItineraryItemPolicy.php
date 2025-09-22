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
        return in_array($user->role, ['admin', 'traveler'], true);
    }

    public function view(User $user, ItineraryItem $item): bool
    {
        // Assumes $item->itinerary relation exists
        return $user->traveler && $item->itinerary?->traveler_id === $user->traveler->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'traveler'], true) && (bool) $user->traveler;
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
