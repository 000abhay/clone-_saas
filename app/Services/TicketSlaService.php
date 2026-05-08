<?php

namespace App\Services;

use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Collection;

class TicketSlaService
{
    public function applyDeadlines(SupportTicket $ticket): SupportTicket
    {
        [$responseHours, $resolutionHours] = match ($ticket->priority) {
            'urgent' => [1, 4],
            'high' => [2, 8],
            'medium' => [8, 24],
            default => [24, 72],
        };

        $createdAt = $ticket->created_at ?? now();

        $ticket->forceFill([
            'sla_response_due_at' => $ticket->sla_response_due_at ?? $createdAt->copy()->addHours($responseHours),
            'sla_resolution_due_at' => $ticket->sla_resolution_due_at ?? $createdAt->copy()->addHours($resolutionHours),
        ]);

        $ticket->save();

        return $ticket;
    }

    public function evaluateBreaches(Collection $tickets): int
    {
        $count = 0;

        foreach ($tickets as $ticket) {
            $responseBreached = $ticket->first_responded_at === null
                && $ticket->sla_response_due_at?->isPast();
            $resolutionBreached = $ticket->resolved_at === null
                && $ticket->sla_resolution_due_at?->isPast();

            if (! $ticket->breached_at && ($responseBreached || $resolutionBreached)) {
                $ticket->forceFill([
                    'breached_at' => now(),
                    'escalated_at' => now(),
                ])->save();

                $count++;
            }
        }

        return $count;
    }
}
