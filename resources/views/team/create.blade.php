@extends('layouts.app')

@section('title', 'Add Team Member')
@section('page_title', 'Add Team Member')
@section('page_subtitle', 'Create a fixed-role user for CRM operations.')

@section('content')
    <form class="panel" method="POST" action="{{ route('team.members.store') }}">
        @csrf
        <div class="panel-head"><strong>Team Member</strong></div>
        <div class="panel-body stack">
            @include('team.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('team.index') }}">Cancel</a>
                <button class="btn" type="submit">Create Member</button>
            </div>
        </div>
    </form>
@endsection
