<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Collection;

class LeadAssignmentService
{
    public function candidates(): Collection
    {
        return User::query()
            ->whereIn('role', ['sales_manager', 'sales_exec'])
            ->where('status', 'active')
            ->orderBy('id')
            ->get();
    }

    public function nextAssignee(): ?User
    {
        $candidates = $this->candidates();

        if ($candidates->isEmpty()) {
            return null;
        }

        $index = Lead::query()
            ->whereNotNull('assigned_user_id')
            ->count() % $candidates->count();

        return $candidates->values()->get($index);
    }

    public function assignIfMissing(Lead $lead): Lead
    {
        if ($lead->assigned_user_id) {
            return $lead;
        }

        $lead->assigned_user_id = $this->nextAssignee()?->id;
        $lead->save();

        return $lead;
    }
}
