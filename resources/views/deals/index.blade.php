@extends('layouts.app')

@section('title', 'Deals')
@section('page_title', 'Deals')
@section('page_subtitle', 'Forecastable pipeline records with stage and probability tracking.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('deals.index', ['format' => 'csv']) }}">Export CSV</a>
        <a class="btn-secondary" href="{{ route('pipeline.index') }}">Open Pipeline</a>
        <a class="btn" href="{{ route('deals.create') }}">New Deal</a>
    </div>
@endsection

@section('content')
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Deal</th>
                        <th>Account</th>
                        <th>Stage</th>
                        <th>Owner</th>
                        <th>Value</th>
                        <th>Probability</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deals as $deal)
                        <tr>
                            <td>
                                <strong>{{ $deal->title }}</strong>
                                <div class="muted">{{ ucfirst($deal->status) }}</div>
                            </td>
                            <td>{{ $deal->account?->name ?? 'No account' }}</td>
                            <td><span class="badge blue">{{ $deal->stage?->name }}</span></td>
                            <td>{{ $deal->owner?->name ?? 'Unassigned' }}</td>
                            <td>${{ number_format((float) $deal->value, 0) }}</td>
                            <td>{{ $deal->probability }}%</td>
                            <td><a class="btn-link" href="{{ route('deals.show', $deal) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="muted">No deals found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    {{ $deals->links() }}
@endsection
