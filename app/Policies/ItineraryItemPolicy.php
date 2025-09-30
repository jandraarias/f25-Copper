<?php

namespace App\Policies;

use App\Models\ItineraryItem;
use App\Models\User;

class ItineraryItemPolicy
{
    /**
     * Admins can do everything.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    /**
     * Travelers can list their own items (enforced by scoping in queries).
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['traveler'], true) && (bool) $user->traveler;
    }

    /**
     * Travelers may only view items that belong to their itinerary.
     */
    public function view(User $user, ItineraryItem $item): bool
    {
        if ($user->role === 'traveler' && $user->traveler) {
            return optional($item->itinerary->traveler)->user_id === $user->id;
        }
        // Experts/Business: deny by default unless collaboration is implemented
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['traveler'], true) && (bool) $user->traveler;
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
