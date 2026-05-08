@extends('layouts.app')

@section('title', 'Edit Task')
@section('page_title', 'Edit Task')
@section('page_subtitle', 'Update assignment, due dates, status, and related records.')

@section('content')
    <form class="panel" method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PATCH')
        <div class="panel-head"><strong>{{ $task->title }}</strong></div>
        <div class="panel-body stack">
            @include('tasks.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('tasks.index') }}">Cancel</a>
                <button class="btn" type="submit">Save Changes</button>
            </div>
        </div>
    </form>
@endsection
