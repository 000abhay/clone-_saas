<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'account_id',
        'contact_id',
        'assignee_id',
        'subject',
        'description',
        'priority',
        'status',
        'sla_response_due_at',
        'sla_resolution_due_at',
        'first_responded_at',
        'resolved_at',
        'breached_at',
        'escalated_at',
    ];

    protected function casts(): array
    {
        return [
            'sla_response_due_at' => 'datetime',
            'sla_resolution_due_at' => 'datetime',
            'first_responded_at' => 'datetime',
            'resolved_at' => 'datetime',
            'breached_at' => 'datetime',
            'escalated_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SupportTicket $ticket): void {
            if ($ticket->ticket_number) {
                return;
            }

            $latestId = (static::max('id') ?? 0) + 1;

            $ticket->ticket_number = 'TKT-'.Str::padLeft((string) $latestId, 4, '0');
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'related');
    }

    public function isBreached(): bool
    {
        return $this->breached_at !== null;
    }
}
