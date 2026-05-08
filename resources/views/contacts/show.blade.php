@extends('layouts.app')

@section('title', $contact->name)
@section('page_title', $contact->name)
@section('page_subtitle', 'Contact profile, related records, and interaction timeline.')
@section('actions')
    @can('update', $contact)
        <a class="btn" href="{{ route('contacts.edit', $contact) }}">Edit Contact</a>
    @endcan
@endsection

@section('content')
    <section class="split">
        <article class="card stack">
            <div><strong>Lifecycle:</strong> <span class="badge blue">{{ \Illuminate\Support\Str::headline($contact->lifecycle_stage) }}</span></div>
            <div><strong>Account:</strong> {{ $contact->account?->name ?? 'No linked account' }}</div>
            <div><strong>Owner:</strong> {{ $contact->owner?->name ?? 'Unassigned' }}</div>
            <div><strong>Email:</strong> {{ $contact->email ?: 'Not set' }}</div>
            <div><strong>Phone:</strong> {{ $contact->phone ?: 'Not set' }}</div>
            <div><strong>Job Title:</strong> {{ $contact->job_title ?: 'Not set' }}</div>
            <div><strong>Tags:</strong> {{ is_array($contact->tags) ? implode(', ', $contact->tags) : 'None' }}</div>
            <div><strong>Custom Fields:</strong>
                <div class="note" style="margin-top:6px;">
                    @if (is_array($contact->custom_fields))
                        @foreach ($contact->custom_fields as $key => $value)
                            <div>{{ $key }}: {{ $value }}</div>
                        @endforeach
                    @else
                        None defined.
                    @endif
                </div>
            </div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Activity Timeline</strong></div>
            <div class="panel-body">
                @include('partials.activity-list', ['activities' => $contact->activities])
            </div>
        </article>
    </section>

    <section class="grid cols-2">
        <article class="panel">
            <div class="panel-head"><strong>Deals</strong></div>
            <div class="panel-body stack">
                @forelse ($contact->deals as $deal)
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
                @forelse ($contact->tickets as $ticket)
                    <a class="card" href="{{ route('tickets.show', $ticket) }}">
                        <strong>{{ $ticket->ticket_number }}</strong>
                        <div style="margin-top:6px;">{{ $ticket->subject }}</div>
                    </a>
                @empty
                    <div class="note">No tickets linked yet.</div>
                @endforelse
            </div>
        </article>
    </section>
@endsection
