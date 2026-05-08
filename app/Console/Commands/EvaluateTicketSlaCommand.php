<?php

namespace App\Console\Commands;

use App\Models\SupportTicket;
use App\Services\ActivityService;
use App\Services\AuditService;
use App\Services\TicketSlaService;
use Illuminate\Console\Command;

class EvaluateTicketSlaCommand extends Command
{
    protected $signature = 'tickets:evaluate-sla';

    protected $description = 'Mark breached support tickets and create audit/activity entries.';

    public function __construct(
        private readonly TicketSlaService $ticketSlaService,
        private readonly ActivityService $activityService,
        private readonly AuditService $auditService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $tickets = SupportTicket::query()
            ->whereIn('status', ['open', 'waiting', 'in_progress'])
            ->get();

        $count = $this->ticketSlaService->evaluateBreaches($tickets);

        if ($count > 0) {
            SupportTicket::query()
                ->whereNotNull('breached_at')
                ->whereDate('breached_at', now()->toDateString())
                ->get()
                ->each(function (SupportTicket $ticket): void {
                    $this->activityService->record($ticket, 'ticket.sla_breached', 'Ticket breached SLA and was escalated');
                    $this->auditService->record('ticket.sla_breached', null, $ticket, null, [
                        'ticket_number' => $ticket->ticket_number,
                    ]);
                });
        }

        $this->info("Processed {$tickets->count()} tickets. Breached: {$count}.");

        return self::SUCCESS;
    }
}
