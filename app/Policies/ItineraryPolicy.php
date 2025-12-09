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


    /* -----------------------------------------------------------------
     |  Shared Permission Checks
     | -----------------------------------------------------------------
     */

    private function isTravelerOwner(User $user, Itinerary $itinerary): bool
    {
        return $user->role === 'traveler'
            && $user->traveler
            && $user->traveler->id === $itinerary->traveler_id;
    }

    private function isTravelerCollaborator(User $user, Itinerary $itinerary): bool
    {
        return $user->role === 'traveler'
            && $itinerary->collaborators()
                ->where('user_id', $user->id)
                ->exists();
    }

    private function isAcceptedExpert(User $user, Itinerary $itinerary): bool
    {
        if ($user->role !== 'expert' || !$user->expert) {
            return false;
        }

        return $itinerary->expertInvitations()
            ->where('expert_id', $user->expert->id)
            ->where('status', 'accepted')
            ->exists();
    }


    /* -----------------------------------------------------------------
     |  View Permissions
     | -----------------------------------------------------------------
     */

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['traveler', 'expert'])
            && ($user->traveler !== null || $user->expert !== null);
    }

    public function view(User $user, Itinerary $itinerary): bool
    {
        return
            $this->isTravelerOwner($user, $itinerary) ||
            $this->isTravelerCollaborator($user, $itinerary) ||
            $this->isAcceptedExpert($user, $itinerary);
    }


    /* -----------------------------------------------------------------
     |  Create Permissions
     | -----------------------------------------------------------------
     */

    public function create(User $user): bool
    {
        return $user->role === 'traveler' && $user->traveler !== null;
    }


    /* -----------------------------------------------------------------
     |  Update Permissions
     | -----------------------------------------------------------------
     */

    public function update(User $user, Itinerary $itinerary): bool
    {
        return
            $this->isTravelerOwner($user, $itinerary) ||
            $this->isTravelerCollaborator($user, $itinerary) ||
            $this->isAcceptedExpert($user, $itinerary);
    }


    /* -----------------------------------------------------------------
     |  Delete Permissions
     | -----------------------------------------------------------------
     */

    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $this->isTravelerOwner($user, $itinerary);
    }


    /* -----------------------------------------------------------------
     |  Expert Invitation Permissions
     | -----------------------------------------------------------------
     */

    public function inviteExpert(User $user, Itinerary $itinerary): bool
    {
        return $this->isTravelerOwner($user, $itinerary);
    }
}
