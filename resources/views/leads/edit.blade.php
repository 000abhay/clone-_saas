@extends('layouts.app')

@section('title', 'Edit Lead')
@section('page_title', 'Edit Lead')
@section('page_subtitle', 'Update assignment, status, source, and scoring inputs.')

@section('content')
    <form class="panel" method="POST" action="{{ route('leads.update', $lead) }}">
        @csrf
        @method('PATCH')
        <div class="panel-head"><strong>{{ $lead->full_name }}</strong></div>
        <div class="panel-body stack">
            @include('leads.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('leads.show', $lead) }}">Cancel</a>
                <button class="btn" type="submit">Save Changes</button>
            </div>
        </div>
    </form>
@endsection
