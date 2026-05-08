@extends('layouts.app')

@section('title', 'Accounts')
@section('page_title', 'Accounts')
@section('page_subtitle', 'Company-level records with linked contacts, pipeline, and support visibility.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('accounts.index', ['format' => 'csv'] + request()->query()) }}">Export CSV</a>
        @can('create', App\Models\Account::class)
            <a class="btn" href="{{ route('accounts.create') }}">New Account</a>
        @endcan
    </div>
@endsection

@section('content')
    <section class="panel">
        <div class="panel-head">
            <form method="GET" action="{{ route('accounts.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; width:100%;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search accounts by name">
                <button class="btn-secondary" type="submit">Filter</button>
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Industry</th>
                        <th>Owner</th>
                        <th>Contacts</th>
                        <th>Deals</th>
                        <th>Tickets</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr>
                            <td>
                                <strong>{{ $account->name }}</strong>
                                <div class="muted">{{ $account->location }}</div>
                            </td>
                            <td>{{ $account->industry ?: 'Not set' }}</td>
                            <td>{{ $account->owner?->name ?? 'Unassigned' }}</td>
                            <td>{{ $account->contacts_count }}</td>
                            <td>{{ $account->deals_count }}</td>
                            <td>{{ $account->tickets_count }}</td>
                            <td><a class="btn-link" href="{{ route('accounts.show', $account) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="muted">No accounts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    {{ $accounts->links() }}
@endsection
