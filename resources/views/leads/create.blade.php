@extends('layouts.app')

@section('title', 'New Lead')
@section('page_title', 'Create Lead')
@section('page_subtitle', 'Capture a lead, score it automatically, and assign ownership.')

@section('content')
    <form class="panel" method="POST" action="{{ route('leads.store') }}">
        @csrf
        <div class="panel-head"><strong>Lead Details</strong></div>
        <div class="panel-body stack">
            @include('leads.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('leads.index') }}">Cancel</a>
                <button class="btn" type="submit">Create Lead</button>
            </div>
        </div>
    </form>
@endsection
