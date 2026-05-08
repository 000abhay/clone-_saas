<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('tickets');
    }

    public function view(User $user, SupportTicket $ticket): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->canManageSupport();
    }

    public function update(User $user, SupportTicket $ticket): bool
    {
        return $user->isAdminLike()
            || $ticket->assignee_id === $user->id
            || $user->canManageSupport();
    }
}
