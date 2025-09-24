<?php

namespace App\Policies;

use App\Models\ItineraryItem;
use App\Models\User;

class ItineraryItemPolicy
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
        return in_array($user->role, ['traveler', 'expert', 'business', 'admin'], true);
    }

    public function view(User $user, ItineraryItem $item): bool
    {
        // User must own the parent itinerary.
        return $item->itinerary && $item->itinerary->user_id === $user->id;
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
