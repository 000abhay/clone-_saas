<?php

namespace App\Policies;

use App\Models\Deal;
use App\Models\User;

class DealPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('pipeline');
    }

    public function view(User $user, Deal $deal): bool
    {
        return $this->viewAny($user)
            && ($user->role !== 'sales_exec' || $deal->owner_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->canManageSalesData();
    }

    public function update(User $user, Deal $deal): bool
    {
        return $this->create($user)
            && ($user->role !== 'sales_exec' || $deal->owner_id === $user->id);
    }
}
