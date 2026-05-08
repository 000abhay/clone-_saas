@extends('layouts.app')

@section('title', 'Edit Contact')
@section('page_title', 'Edit Contact')
@section('page_subtitle', 'Update lifecycle, ownership, tags, and account context.')

@section('content')
    <form class="panel" method="POST" action="{{ route('contacts.update', $contact) }}">
        @csrf
        @method('PATCH')
        <div class="panel-head"><strong>{{ $contact->name }}</strong></div>
        <div class="panel-body stack">
            @include('contacts.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('contacts.show', $contact) }}">Cancel</a>
                <button class="btn" type="submit">Save Changes</button>
            </div>
        </div>
    </form>
@endsection
