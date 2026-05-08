@extends('layouts.app')

@section('title', $account->name)
@section('page_title', $account->name)
@section('page_subtitle', 'Account overview with related contacts, pipeline, support, and activity.')
@section('actions')
    <div class="actions">
        @can('update', $account)
            <a class="btn" href="{{ route('accounts.edit', $account) }}">Edit Account</a>
        @endcan
    </div>
@endsection

@section('content')
    <section class="split">
        <article class="card stack">
            <div><strong>Status</strong> <span class="badge blue">{{ ucfirst($account->status) }}</span></div>
            <div><strong>Industry:</strong> {{ $account->industry ?: 'Not set' }}</div>
            <div><strong>Owner:</strong> {{ $account->owner?->name ?? 'Unassigned' }}</div>
            <div><strong>Website:</strong> {{ $account->website ?: 'Not set' }}</div>
            <div><strong>Email:</strong> {{ $account->email ?: 'Not set' }}</div>
            <div><strong>Phone:</strong> {{ $account->phone ?: 'Not set' }}</div>
            <div><strong>Location:</strong> {{ $account->location ?: 'Not set' }}</div>
            <div><strong>Notes:</strong><div class="muted" style="margin-top:6px;">{{ $account->notes ?: 'No notes yet.' }}</div></div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Recent Activity</strong></div>
            <div class="panel-body">
                @include('partials.activity-list', ['activities' => $account->activities])
            </div>
        </article>
    </section>

    <section class="grid cols-3">
        <article class="panel">
            <div class="panel-head"><strong>Contacts</strong></div>
            <div class="panel-body stack">
                @forelse ($account->contacts as $contact)
                    <a class="card" href="{{ route('contacts.show', $contact) }}">
                        <strong>{{ $contact->name }}</strong>
                        <div class="muted" style="margin-top:6px;">{{ $contact->lifecycle_stage }} · {{ $contact->email }}</div>
                    </a>
                @empty
                    <div class="note">No contacts linked yet.</div>
                @endforelse
            </div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Deals</strong></div>
            <div class="panel-body stack">
                @forelse ($account->deals as $deal)
                    <a class="card" href="{{ route('deals.show', $deal) }}">
                        <strong>{{ $deal->title }}</strong>
                        <div class="muted" style="margin-top:6px;">{{ $deal->stage?->name }} · ${{ number_format((float) $deal->value, 0) }}</div>
                    </a>
                @empty
                    <div class="note">No deals linked yet.</div>
                @endforelse
            </div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Support Tickets</strong></div>
            <div class="panel-body stack">
                @forelse ($account->tickets as $ticket)
                    <a class="card" href="{{ route('tickets.show', $ticket) }}">
                        <strong>{{ $ticket->ticket_number }}</strong>
                        <div style="margin-top:6px;">{{ $ticket->subject }}</div>
                    </a>
                @empty
                    <div class="note">No support tickets linked yet.</div>
                @endforelse
            </div>
        </article>
    </section>
@endsection
