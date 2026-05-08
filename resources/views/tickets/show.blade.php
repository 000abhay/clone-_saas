@extends('layouts.app')

@section('title', $ticket->ticket_number)
@section('page_title', $ticket->ticket_number)
@section('page_subtitle', 'Support ticket details, SLA state, linked work, and queue actions.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('tickets.edit', $ticket) }}">Edit Ticket</a>
        <form method="POST" action="{{ route('tickets.sla.refresh', $ticket) }}" class="inline">
            @csrf
            @method('PATCH')
            <button class="btn-secondary" type="submit">Refresh SLA</button>
        </form>
    </div>
@endsection

@section('content')
    <section class="split">
        <article class="card stack">
            <div><strong>Subject:</strong> {{ $ticket->subject }}</div>
            <div><strong>Account:</strong> {{ $ticket->account?->name ?? 'No account' }}</div>
            <div><strong>Contact:</strong> {{ $ticket->contact?->name ?? 'No contact' }}</div>
            <div><strong>Assignee:</strong> {{ $ticket->assignee?->name ?? 'Unassigned' }}</div>
            <div><strong>Priority:</strong> <span class="badge {{ in_array($ticket->priority, ['high', 'urgent'], true) ? 'danger' : 'warn' }}">{{ ucfirst($ticket->priority) }}</span></div>
            <div><strong>Status:</strong> <span class="badge blue">{{ \Illuminate\Support\Str::headline($ticket->status) }}</span></div>
            <div><strong>Response Due:</strong> {{ $ticket->sla_response_due_at?->format('M j, Y g:i A') ?? 'Pending' }}</div>
            <div><strong>Resolution Due:</strong> {{ $ticket->sla_resolution_due_at?->format('M j, Y g:i A') ?? 'Pending' }}</div>
            <div><strong>Breached:</strong> {{ $ticket->breached_at ? $ticket->breached_at->diffForHumans() : 'No' }}</div>
            <div class="note">{{ $ticket->description ?: 'No description yet.' }}</div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Queue Actions</strong></div>
            <div class="panel-body stack">
                <form method="POST" action="{{ route('tickets.status.update', $ticket) }}" class="stack">
                    @csrf
                    @method('PATCH')
                    <label class="field">
                        <span>Update Status</span>
                        <select name="status">
                            @foreach (\App\Support\CrmOptions::ticketStatuses() as $key => $label)
                                <option value="{{ $key }}" @selected($ticket->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button class="btn" type="submit">Save Status</button>
                </form>

                <form method="POST" action="{{ route('tickets.assignment.update', $ticket) }}" class="stack">
                    @csrf
                    @method('PATCH')
                    <label class="field">
                        <span>Assign Ticket</span>
                        <select name="assignee_id">
                            <option value="">Unassigned</option>
                            @foreach (\App\Models\User::whereIn('role', ['support_agent', 'admin', 'super_admin'])->where('status', 'active')->orderBy('name')->get() as $assignee)
                                <option value="{{ $assignee->id }}" @selected($ticket->assignee_id === $assignee->id)>{{ $assignee->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button class="btn-secondary" type="submit">Update Assignee</button>
                </form>
            </div>
        </article>
    </section>

    <section class="grid cols-2">
        <article class="panel">
            <div class="panel-head"><strong>Activity Timeline</strong></div>
            <div class="panel-body">
                @include('partials.activity-list', ['activities' => $ticket->activities])
            </div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Linked Tasks</strong></div>
            <div class="panel-body stack">
                @forelse ($ticket->tasks as $task)
                    <div class="card">
                        <strong>{{ $task->title }}</strong>
                        <div class="muted" style="margin-top:6px;">{{ \Illuminate\Support\Str::headline($task->status) }} · {{ $task->assignedUser?->name ?? 'Unassigned' }}</div>
                    </div>
                @empty
                    <div class="note">No tasks linked to this ticket yet.</div>
                @endforelse
            </div>
        </article>
    </section>
@endsection
