<?php

namespace App\Policies;

use App\Models\Memorial;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MemorialPolicy
{
    /**
     * Determine if the contributor can view their memorial.
     */
    public function view(User $user, Memorial $memorial): bool
    {
        // Log for debugging in production
        Log::info('MemorialPolicy::view check', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'memorial_id' => $memorial->id,
            'memorial_user_id' => $memorial->user_id,
            'is_admin' => $user->isAdmin(),
            'is_owner' => $user->id === $memorial->user_id,
        ]);
        
        $result = $user->isAdmin() || $user->id === $memorial->user_id;
        
        Log::info('MemorialPolicy::view result', ['allowed' => $result]);
        
        return $result;
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
