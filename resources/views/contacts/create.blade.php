@extends('layouts.app')

@section('title', 'New Contact')
@section('page_title', 'Create Contact')
@section('page_subtitle', 'Create a contact profile with lifecycle tags and ownership.')

@section('content')
    <form class="panel" method="POST" action="{{ route('contacts.store') }}">
        @csrf
        <div class="panel-head"><strong>Contact Details</strong></div>
        <div class="panel-body stack">
            @include('contacts.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('contacts.index') }}">Cancel</a>
                <button class="btn" type="submit">Create Contact</button>
            </div>
        </div>
    </form>
@endsection
