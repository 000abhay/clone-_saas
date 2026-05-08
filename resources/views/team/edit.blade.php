@extends('layouts.app')

@section('title', 'Edit Team Member')
@section('page_title', 'Edit Team Member')
@section('page_subtitle', 'Update role, status, and access context for this user.')

@section('content')
    <form class="panel" method="POST" action="{{ route('team.members.update', $member) }}">
        @csrf
        @method('PATCH')
        <div class="panel-head"><strong>{{ $member->name }}</strong></div>
        <div class="panel-body stack">
            @include('team.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('team.index') }}">Cancel</a>
                <button class="btn" type="submit">Save Changes</button>
            </div>
        </div>
    </form>
@endsection
