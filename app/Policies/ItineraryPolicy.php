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
        return in_array($user->role, ['traveler', 'expert']) && 
               ($user->traveler !== null || $user->expert !== null);
    }

    /**
     * Determine if the user can view a specific itinerary.
     *
     * Owners, collaborators, and accepted expert invitees can view.
     */
    public function view(User $user, Itinerary $itinerary): bool
    {
        // Traveler owner can view
        $travelerId = $user->traveler?->id;
        if ($user->role === 'traveler' && $travelerId === $itinerary->traveler_id) {
            return true;
        }

        // Traveler collaborators can view
        if ($user->role === 'traveler' &&
            $itinerary->collaborators()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // Expert with accepted invitation can view
        if ($user->role === 'expert') {
            $expert = $user->expert;
            if ($expert &&
                $itinerary->expertInvitations()
                    ->where('expert_id', $expert->id)
                    ->where('status', 'accepted')
                    ->exists()) {
                return true;
            }
        }

        return false;
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
     * Owners, collaborators, and accepted expert invitees can update.
     */
    public function update(User $user, Itinerary $itinerary): bool
    {
        // Traveler owner can update
        $travelerId = $user->traveler?->id;
        if ($user->role === 'traveler' && $travelerId === $itinerary->traveler_id) {
            return true;
        }

        // Traveler collaborators can update
        if ($user->role === 'traveler' &&
            $itinerary->collaborators()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // Expert with accepted invitation can update
        if ($user->role === 'expert') {
            $expert = $user->expert;
            if ($expert &&
                $itinerary->expertInvitations()
                    ->where('expert_id', $expert->id)
                    ->where('status', 'accepted')
                    ->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user can delete the itinerary.
     *
     * Only the owner (not collaborators or experts) can delete.
     */
    public function delete(User $user, Itinerary $itinerary): bool
    {
        $travelerId = $user->traveler?->id;

        return $user->role === 'traveler'
            && $travelerId !== null
            && $travelerId === $itinerary->traveler_id;
    }

    /**
     * Determine if the user can invite an expert to the itinerary.
     *
     * Only the itinerary owner can invite expert collaborators.
     */
    public function inviteExpert(User $user, Itinerary $itinerary): bool
    {
        // Only travelers can invite experts
        if ($user->role !== 'traveler') {
            return false;
        }

        // Only the traveler who owns the itinerary may invite experts
        return $user->traveler &&
               $user->traveler->id === $itinerary->traveler_id;
    }
}
