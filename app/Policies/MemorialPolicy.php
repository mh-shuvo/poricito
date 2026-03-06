<?php

namespace App\Policies;

use App\Models\Memorial;
use App\Models\User;

class MemorialPolicy
{
    /**
     * Determine if the contributor can view their memorial.
     */
    public function view(User $user, Memorial $memorial): bool
    {
        return $user->isAdmin() || $user->id === $memorial->user_id;
    }

    /**
     * Determine if the contributor can update their memorial (only pending/rejected).
     */
    public function update(User $user, Memorial $memorial): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $memorial->user_id && 
               in_array($memorial->status, ['pending', 'rejected']);
    }

    /**
     * Determine if the contributor can delete their memorial.
     */
    public function delete(User $user, Memorial $memorial): bool
    {
        return $user->isAdmin() || $user->id === $memorial->user_id;
    }
}
