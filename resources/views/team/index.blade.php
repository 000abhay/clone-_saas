@extends('layouts.app')

@section('title', 'Team')
@section('page_title', 'Team Management')
@section('page_subtitle', 'Manage fixed CRM roles, account status, and access by user.')
@section('actions')
    <a class="btn" href="{{ route('team.members.create') }}">Add Team Member</a>
@endsection

@section('content')
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr>
                            <td><strong>{{ $member->name }}</strong></td>
                            <td>{{ $member->email }}</td>
                            <td><span class="badge blue">{{ $member->roleLabel() }}</span></td>
                            <td>{{ $member->statusLabel() }}</td>
                            <td>{{ $member->last_active_at?->diffForHumans() ?? 'Never' }}</td>
                            <td><a class="btn-link" href="{{ route('team.members.edit', $member) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    {{ $members->links() }}
@endsection
