<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;

class ContactPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('contacts');
    }

    public function view(User $user, Contact $contact): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->canManageSalesData() || $user->isAdminLike();
    }

    public function update(User $user, Contact $contact): bool
    {
        return $this->create($user);
    }
}
