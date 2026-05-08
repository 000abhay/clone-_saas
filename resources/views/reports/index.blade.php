@extends('layouts.app')

@section('title', 'Reports')
@section('page_title', 'Reports')
@section('page_subtitle', 'Role-based operational reporting with CSV export support.')
@section('actions')
    <a class="btn" href="{{ route('reports.index', ['format' => 'csv']) }}">Export Summary CSV</a>
@endsection

@section('content')
    <section class="metric-grid">
        @foreach ($summaryReport as $row)
            <article class="metric">
                <div class="label">{{ $row['metric'] }}</div>
                <div class="value">{{ $row['value'] }}</div>
            </article>
        @endforeach
    </section>

    <section class="grid cols-2">
        <article class="panel">
            <div class="panel-head"><strong>Lead Sources</strong></div>
            <div class="panel-body kpi-list">
                @foreach ($leadSources as $source => $total)
                    <div class="kpi-row">
                        <span>{{ \Illuminate\Support\Str::headline($source) }}</span>
                        <strong>{{ $total }}</strong>
                    </div>
                @endforeach
            </div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Task Statuses</strong></div>
            <div class="panel-body kpi-list">
                @foreach ($taskStatuses as $status => $total)
                    <div class="kpi-row">
                        <span>{{ \Illuminate\Support\Str::headline($status) }}</span>
                        <strong>{{ $total }}</strong>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    <section class="grid cols-2">
        <article class="panel">
            <div class="panel-head"><strong>Pipeline Value</strong></div>
            <div class="panel-body kpi-list">
                @forelse ($dealPipeline as $row)
                    <div class="kpi-row">
                        <span>{{ $row->name }}</span>
                        <strong>${{ number_format((float) $row->value, 0) }} · {{ $row->total }} deals</strong>
                    </div>
                @empty
                    <div class="note">No pipeline data available yet.</div>
                @endforelse
            </div>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Ticket Health</strong></div>
            <div class="panel-body kpi-list">
                @foreach ($ticketHealth as $priority => $total)
                    <div class="kpi-row">
                        <span>{{ ucfirst($priority) }}</span>
                        <strong>{{ $total }}</strong>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    <section class="panel">
        <div class="panel-head"><strong>Recent Activity</strong></div>
        <div class="panel-body">
            @include('partials.activity-list', ['activities' => $recentActivity])
        </div>
    </section>
@endsection
