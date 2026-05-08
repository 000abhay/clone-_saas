<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    public function record(
        string $action,
        ?User $user = null,
        ?Model $auditable = null,
        ?string $ipAddress = null,
        array $meta = [],
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user?->id,
            'auditable_type' => $auditable?->getMorphClass(),
            'auditable_id' => $auditable?->getKey(),
            'action' => $action,
            'ip_address' => $ipAddress,
            'meta' => $meta ?: null,
        ]);
    }
}
