@extends('layouts.app')

@section('title', 'Edit Ticket')
@section('page_title', 'Edit Ticket')
@section('page_subtitle', 'Update ownership, status, description, and SLA-related data.')

@section('content')
    <form class="panel" method="POST" action="{{ route('tickets.update', $ticket) }}">
        @csrf
        @method('PATCH')
        <div class="panel-head"><strong>{{ $ticket->ticket_number }}</strong></div>
        <div class="panel-body stack">
            @include('tickets.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('tickets.show', $ticket) }}">Cancel</a>
                <button class="btn" type="submit">Save Changes</button>
            </div>
        </div>
    </form>
@endsection
