<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('leads');
    }

    public function view(User $user, Lead $lead): bool
    {
        return $this->viewAny($user)
            && ($user->role !== 'sales_exec' || $lead->assigned_user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->canManageSalesData();
    }

    public function update(User $user, Lead $lead): bool
    {
        return $this->create($user)
            && ($user->role !== 'sales_exec' || $lead->assigned_user_id === $user->id);
    }

    public function convert(User $user, Lead $lead): bool
    {
        return $this->create($user) && ! $lead->isConverted();
    }
}
