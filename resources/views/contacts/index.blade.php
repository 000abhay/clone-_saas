@extends('layouts.app')

@section('title', 'Contacts')
@section('page_title', 'Contacts')
@section('page_subtitle', '360-degree contact records with lifecycle, tags, and CRM history.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('contacts.index', ['format' => 'csv'] + request()->query()) }}">Export CSV</a>
        @can('create', App\Models\Contact::class)
            <a class="btn" href="{{ route('contacts.create') }}">New Contact</a>
        @endcan
    </div>
@endsection

@section('content')
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Account</th>
                        <th>Lifecycle</th>
                        <th>Owner</th>
                        <th>Tags</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts as $contact)
                        <tr>
                            <td>
                                <strong>{{ $contact->name }}</strong>
                                <div class="muted">{{ $contact->email ?: $contact->phone }}</div>
                            </td>
                            <td>{{ $contact->account?->name ?? 'No account' }}</td>
                            <td><span class="badge blue">{{ \Illuminate\Support\Str::headline($contact->lifecycle_stage) }}</span></td>
                            <td>{{ $contact->owner?->name ?? 'Unassigned' }}</td>
                            <td>{{ is_array($contact->tags) ? implode(', ', $contact->tags) : 'None' }}</td>
                            <td><a class="btn-link" href="{{ route('contacts.show', $contact) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="muted">No contacts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    {{ $contacts->links() }}
@endsection
