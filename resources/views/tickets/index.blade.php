@extends('layouts.app')

@section('title', 'Support')
@section('page_title', 'Support Tickets')
@section('page_subtitle', 'SLA-aware support queue ordered by urgency and breach risk.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('tickets.index', ['format' => 'csv']) }}">Export CSV</a>
        @can('create', App\Models\SupportTicket::class)
            <a class="btn" href="{{ route('tickets.create') }}">New Ticket</a>
        @endcan
    </div>
@endsection

@section('content')
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Account</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>SLA</th>
                        <th>Assignee</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>
                                <strong>{{ $ticket->ticket_number }}</strong>
                                <div>{{ $ticket->subject }}</div>
                            </td>
                            <td>{{ $ticket->account?->name ?? 'No account' }}</td>
                            <td><span class="badge blue">{{ \Illuminate\Support\Str::headline($ticket->status) }}</span></td>
                            <td><span class="badge {{ in_array($ticket->priority, ['high', 'urgent'], true) ? 'danger' : 'warn' }}">{{ ucfirst($ticket->priority) }}</span></td>
                            <td>
                                @if ($ticket->breached_at)
                                    <span class="badge danger">Breached</span>
                                @else
                                    {{ $ticket->sla_resolution_due_at?->diffForHumans() ?? 'Pending' }}
                                @endif
                            </td>
                            <td>{{ $ticket->assignee?->name ?? 'Unassigned' }}</td>
                            <td><a class="btn-link" href="{{ route('tickets.show', $ticket) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="muted">No tickets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    {{ $tickets->links() }}
@endsection
