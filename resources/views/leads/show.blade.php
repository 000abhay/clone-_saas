@extends('layouts.app')

@section('title', $lead->full_name)
@section('page_title', $lead->full_name)
@section('page_subtitle', 'Lead details, assignment, tasks, activity, and conversion workflow.')
@section('actions')
    <div class="actions">
        @can('update', $lead)
            <a class="btn-secondary" href="{{ route('leads.edit', $lead) }}">Edit Lead</a>
        @endcan
        @can('convert', $lead)
            <form method="POST" action="{{ route('leads.convert', $lead) }}" class="inline">
                @csrf
                <button class="btn" type="submit">Convert to Contact</button>
            </form>
        @endcan
    </div>
@endsection

@section('content')
    <section class="split">
        <article class="card stack">
            <div><strong>Status:</strong> <span class="badge blue">{{ \Illuminate\Support\Str::headline($lead->status) }}</span></div>
            <div><strong>Score:</strong> {{ $lead->score }}</div>
            <div><strong>Company:</strong> {{ $lead->company_name ?: 'Not set' }}</div>
            <div><strong>Source:</strong> {{ \Illuminate\Support\Str::headline($lead->source) }}</div>
            <div><strong>Assigned To:</strong> {{ $lead->assignedUser?->name ?? 'Unassigned' }}</div>
            <div><strong>Email:</strong> {{ $lead->email ?: 'Not set' }}</div>
            <div><strong>Phone:</strong> {{ $lead->phone ?: 'Not set' }}</div>
            <div><strong>Notes:</strong><div class="note" style="margin-top:6px;">{{ $lead->notes ?: 'No notes yet.' }}</div></div>
            @if ($lead->contact)
                <div><strong>Converted Contact:</strong> <a class="btn-link" href="{{ route('contacts.show', $lead->contact) }}">{{ $lead->contact->name }}</a></div>
            @endif
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Activity Timeline</strong></div>
            <div class="panel-body">
                @include('partials.activity-list', ['activities' => $lead->activities])
            </div>
        </article>
    </section>

    <section class="grid cols-2">
        <article class="panel">
            <div class="panel-head"><strong>Related Tasks</strong></div>
            <div class="panel-body stack">
                @forelse ($lead->tasks as $task)
                    <div class="card">
                        <strong>{{ $task->title }}</strong>
                        <div class="muted" style="margin-top:6px;">{{ \Illuminate\Support\Str::headline($task->status) }} · {{ $task->assignedUser?->name ?? 'Unassigned' }}</div>
                    </div>
                @empty
                    <div class="note">No tasks linked yet.</div>
                @endforelse
            </div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Quick Convert</strong></div>
            <div class="panel-body">
                @if ($lead->contact)
                    <div class="flash">This lead has already been converted.</div>
                @else
                    <form method="POST" action="{{ route('leads.convert', $lead) }}" class="stack">
                        @csrf
                        <label class="field">
                            <span>Account Name</span>
                            <input type="text" name="account_name" value="{{ old('account_name', $lead->company_name) }}">
                        </label>
                        <label class="field">
                            <span>Contact Name</span>
                            <input type="text" name="contact_name" value="{{ old('contact_name', $lead->full_name) }}">
                        </label>
                        <label class="field">
                            <span>Lifecycle Stage</span>
                            <select name="lifecycle_stage">
                                @foreach (\App\Support\CrmOptions::contactLifecycleStages() as $key => $label)
                                    <option value="{{ $key }}" @selected($key === 'customer')>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <button class="btn" type="submit">Convert Lead</button>
                    </form>
                @endif
            </div>
        </article>
    </section>
@endsection
