<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tickets\TicketAssignmentRequest;
use App\Http\Requests\Tickets\TicketRequest;
use App\Http\Requests\Tickets\TicketStatusRequest;
use App\Models\Account;
use App\Models\Contact;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\AuditService;
use App\Services\CsvExportService;
use App\Services\TicketSlaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService,
        private readonly AuditService $auditService,
        private readonly CsvExportService $csvExportService,
        private readonly TicketSlaService $ticketSlaService,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', SupportTicket::class);

        $tickets = SupportTicket::query()
            ->with(['account', 'contact', 'assignee'])
            ->when($request->user()->role === 'support_agent', fn ($query) => $query->where('assignee_id', $request->user()->id))
            ->orderByRaw('breached_at is not null desc')
            ->orderBy('sla_resolution_due_at')
            ->paginate(15)
            ->withQueryString();

        if ($request->query('format') === 'csv') {
            return $this->csvExportService->download('tickets.csv', ['Ticket', 'Subject', 'Priority', 'Status', 'Assignee'], $tickets->getCollection()->map(fn (SupportTicket $ticket) => [
                $ticket->ticket_number,
                $ticket->subject,
                $ticket->priority,
                $ticket->status,
                $ticket->assignee?->name,
            ]));
        }

        return view('tickets.index', [
            'tickets' => $tickets,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', SupportTicket::class);

        return view('tickets.create', $this->formData(new SupportTicket()));
    }

    public function store(TicketRequest $request): RedirectResponse
    {
        $this->authorize('create', SupportTicket::class);

        $ticket = SupportTicket::create($request->validated());
        $this->ticketSlaService->applyDeadlines($ticket);
        $this->activityService->record($ticket, 'ticket.created', 'Ticket created', $request->user());

        return redirect()->route('tickets.show', $ticket)->with('status', 'Ticket created successfully.');
    }

    public function show(SupportTicket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['account', 'contact', 'assignee', 'activities.user', 'tasks.assignedUser']);

        return view('tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    public function edit(SupportTicket $ticket): View
    {
        $this->authorize('update', $ticket);

        return view('tickets.edit', $this->formData($ticket));
    }

    public function update(TicketRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $ticket->update($request->validated());
        if ($ticket->status === 'in_progress' && ! $ticket->first_responded_at) {
            $ticket->forceFill(['first_responded_at' => now()])->save();
        }
        if (in_array($ticket->status, ['resolved', 'closed'], true) && ! $ticket->resolved_at) {
            $ticket->forceFill(['resolved_at' => now()])->save();
        }
        $this->ticketSlaService->applyDeadlines($ticket);
        $this->activityService->record($ticket, 'ticket.updated', 'Ticket updated', $request->user());

        return redirect()->route('tickets.show', $ticket)->with('status', 'Ticket updated successfully.');
    }

    public function updateStatus(TicketStatusRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $ticket->status = $request->string('status')->toString();

        if ($ticket->status === 'in_progress' && ! $ticket->first_responded_at) {
            $ticket->first_responded_at = now();
        }

        if (in_array($ticket->status, ['resolved', 'closed'], true) && ! $ticket->resolved_at) {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        $this->activityService->record($ticket, 'ticket.status_changed', 'Ticket status changed to '.$ticket->status, $request->user());

        return back()->with('status', 'Ticket status updated.');
    }

    public function updateAssignment(TicketAssignmentRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $ticket->update($request->validated());
        $this->activityService->record($ticket, 'ticket.reassigned', 'Ticket assignment updated', $request->user());
        $this->auditService->record('ticket.assigned', $request->user(), $ticket, $request->ip(), [
            'assignee_id' => $ticket->assignee_id,
        ]);

        return back()->with('status', 'Ticket assignee updated.');
    }

    public function refreshSla(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $this->ticketSlaService->applyDeadlines($ticket->forceFill([
            'sla_response_due_at' => null,
            'sla_resolution_due_at' => null,
        ]));
        $this->activityService->record($ticket, 'ticket.sla_refreshed', 'SLA deadlines recalculated', $request->user());

        return back()->with('status', 'Ticket SLA refreshed.');
    }

    private function formData(SupportTicket $ticket): array
    {
        return [
            'ticket' => $ticket,
            'accounts' => Account::query()->orderBy('name')->get(),
            'contacts' => Contact::query()->orderBy('name')->get(),
            'assignees' => User::query()->whereIn('role', ['support_agent', 'admin', 'super_admin'])->where('status', 'active')->orderBy('name')->get(),
        ];
    }
}
