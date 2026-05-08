@extends('layouts.app')

@section('title', 'New Task')
@section('page_title', 'Create Task')
@section('page_subtitle', 'Link work items to the right CRM record and assignee.')

@section('content')
    <form class="panel" method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="panel-head"><strong>Task Details</strong></div>
        <div class="panel-body stack">
            @include('tasks.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('tasks.index') }}">Cancel</a>
                <button class="btn" type="submit">Create Task</button>
            </div>
        </div>
    </form>
@endsection
