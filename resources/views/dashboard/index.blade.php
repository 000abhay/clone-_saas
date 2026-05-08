@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Live CRM health across leads, revenue, tasks, and support queues.')

@section('content')
    <section class="metric-grid">
        @foreach ($metrics as $label => $value)
            <article class="metric">
                <div class="label">{{ $label }}</div>
                <div class="value">{{ $value }}</div>
            </article>
        @endforeach
    </section>

    <section class="split">
        <article class="panel">
            <div class="panel-head">
                <div>
                    <strong>Lead Funnel</strong>
                    <div class="muted">Current distribution by lifecycle stage.</div>
                </div>
            </div>
            <div class="panel-body kpi-list">
                @forelse ($leadFunnel as $status => $total)
                    <div class="kpi-row">
                        <span>{{ \Illuminate\Support\Str::headline($status) }}</span>
                        <strong>{{ $total }}</strong>
                    </div>
                @empty
                    <div class="note">No lead activity yet.</div>
                @endforelse
            </div>
        </article>

        <article class="panel">
            <div class="panel-head">
                <div>
                    <strong>Ticket Heat</strong>
                    <div class="muted">Open support workload by priority.</div>
                </div>
            </div>
            <div class="panel-body kpi-list">
                @forelse ($ticketSummary as $priority => $total)
                    <div class="kpi-row">
                        <span>{{ ucfirst($priority) }}</span>
                        <strong>{{ $total }}</strong>
                    </div>
                @empty
                    <div class="note">No open tickets right now.</div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="grid cols-3">
        <article class="panel">
            <div class="panel-head">
                <strong>Priority Tasks</strong>
            </div>
            <div class="panel-body stack">
                @forelse ($myTasks as $task)
                    <div class="card">
                        <div style="display:flex; justify-content:space-between; gap:12px;">
                            <strong>{{ $task->title }}</strong>
                            <span class="badge {{ in_array($task->priority, ['high', 'urgent'], true) ? 'danger' : 'blue' }}">{{ ucfirst($task->priority) }}</span>
                        </div>
                        <div class="muted" style="margin-top:6px;">{{ $task->status }} @if($task->due_date) · due {{ $task->due_date->format('M j') }} @endif</div>
                    </div>
                @empty
                    <div class="note">No tasks assigned in this view.</div>
                @endforelse
            </div>
        </article>

        <article class="panel">
            <div class="panel-head">
                <strong>Pipeline Snapshot</strong>
            </div>
            <div class="panel-body stack">
                @forelse ($myDeals as $deal)
                    <div class="card">
                        <div style="display:flex; justify-content:space-between; gap:12px;">
                            <strong>{{ $deal->title }}</strong>
                            <span class="badge blue">{{ $deal->stage?->name }}</span>
                        </div>
                        <div style="margin-top:8px;">{{ $deal->account?->name ?? 'No account linked' }}</div>
                        <div class="muted" style="margin-top:6px;">${{ number_format((float) $deal->value, 0) }} · {{ $deal->probability }}% probability</div>
                    </div>
                @empty
                    <div class="note">No open deals in scope.</div>
                @endforelse
            </div>
        </article>

        <article class="panel">
            <div class="panel-head">
                <strong>Support Queue</strong>
            </div>
            <div class="panel-body stack">
                @forelse ($myTickets as $ticket)
                    <div class="card">
                        <div style="display:flex; justify-content:space-between; gap:12px;">
                            <strong>{{ $ticket->ticket_number }}</strong>
                            <span class="badge {{ $ticket->breached_at ? 'danger' : 'warn' }}">{{ ucfirst($ticket->priority) }}</span>
                        </div>
                        <div style="margin-top:8px;">{{ $ticket->subject }}</div>
                        <div class="muted" style="margin-top:6px;">{{ $ticket->account?->name ?? 'No account' }} · {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</div>
                    </div>
                @empty
                    <div class="note">No active tickets in scope.</div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <strong>Recent Activity</strong>
                <div class="muted">Cross-team timeline of CRM changes.</div>
            </div>
        </div>
        <div class="panel-body">
            @include('partials.activity-list', ['activities' => $teamActivity])
        </div>
    </section>
@endsection
