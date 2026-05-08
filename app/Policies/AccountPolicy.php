<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class AccountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('accounts');
    }

    public function view(User $user, Account $account): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->canManageSalesData() || $user->isAdminLike();
    }

    public function update(User $user, Account $account): bool
    {
        return $this->create($user);
    }
}
