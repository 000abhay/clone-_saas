<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityService
{
    public function record(Model $subject, string $type, string $description, ?User $user = null, array $meta = []): Activity
    {
        return $subject->activities()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'description' => $description,
            'meta' => $meta ?: null,
        ]);
    }
}
