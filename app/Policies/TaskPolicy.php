<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('tasks');
    }

    public function view(User $user, Task $task): bool
    {
        return $this->viewAny($user)
            && (! in_array($user->role, ['sales_exec', 'support_agent'], true) || $task->assigned_user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->canAccessModule('tasks');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->isAdminLike()
            || $task->assigned_user_id === $user->id
            || $user->canManageSalesData()
            || $user->canManageSupport();
    }
}
