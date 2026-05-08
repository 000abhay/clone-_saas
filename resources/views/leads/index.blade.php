@extends('layouts.app')

@section('title', 'Leads')
@section('page_title', 'Leads')
@section('page_subtitle', 'Scored lead intake, duplicate prevention, assignment, import, and conversion.')
@section('actions')
    <div class="actions">
        <span class="badge warn">Unassigned: {{ $unassignedCount }}</span>
        <a class="btn-secondary" href="{{ route('leads.index', ['format' => 'csv'] + request()->query()) }}">Export CSV</a>
        <a class="btn" href="{{ route('leads.create') }}">New Lead</a>
    </div>
@endsection

@section('content')
    <section class="panel">
        <div class="panel-head">
            <form method="GET" action="{{ route('leads.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; width:100%;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by lead or company">
                <select name="status">
                    <option value="">All statuses</option>
                    @foreach (\App\Support\CrmOptions::leadStatuses() as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn-secondary" type="submit">Filter</button>
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Lead</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Assignee</th>
                        <th>Conversion</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>
                                <strong>{{ $lead->full_name }}</strong>
                                <div class="muted">{{ $lead->company_name ?: 'No company' }}</div>
                            </td>
                            <td>{{ \Illuminate\Support\Str::headline($lead->source) }}</td>
                            <td><span class="badge blue">{{ \Illuminate\Support\Str::headline($lead->status) }}</span></td>
                            <td><strong>{{ $lead->score }}</strong></td>
                            <td>{{ $lead->assignedUser?->name ?? 'Unassigned' }}</td>
                            <td>{{ $lead->contact ? 'Converted' : 'Open' }}</td>
                            <td><a class="btn-link" href="{{ route('leads.show', $lead) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="muted">No leads found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <strong>CSV Import</strong>
                <div class="muted">Header example: <code>first_name,last_name,company_name,email,phone,source,status,notes</code></div>
            </div>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('leads.import') }}" enctype="multipart/form-data" class="actions">
                @csrf
                <input type="file" name="file" accept=".csv,text/csv" required>
                <button class="btn" type="submit">Import Leads</button>
            </form>
        </div>
    </section>

    {{ $leads->links() }}
@endsection
