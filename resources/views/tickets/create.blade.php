@extends('layouts.app')

@section('title', 'New Ticket')
@section('page_title', 'Create Ticket')
@section('page_subtitle', 'Start a support case with SLA deadlines based on priority.')

@section('content')
    <form class="panel" method="POST" action="{{ route('tickets.store') }}">
        @csrf
        <div class="panel-head"><strong>Ticket Details</strong></div>
        <div class="panel-body stack">
            @include('tickets.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('tickets.index') }}">Cancel</a>
                <button class="btn" type="submit">Create Ticket</button>
            </div>
        </div>
    </form>
@endsection
