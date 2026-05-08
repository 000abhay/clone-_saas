<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageTeam();
    }

    public function create(User $user): bool
    {
        return $user->canManageTeam();
    }

    public function update(User $user, User $member): bool
    {
        return $user->canManageTeam();
    }
}
