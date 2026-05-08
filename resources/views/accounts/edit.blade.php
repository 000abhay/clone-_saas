@extends('layouts.app')

@section('title', 'Edit Account')
@section('page_title', 'Edit Account')
@section('page_subtitle', 'Update company details, ownership, and CRM notes.')

@section('content')
    <form class="panel" method="POST" action="{{ route('accounts.update', $account) }}">
        @csrf
        @method('PATCH')
        <div class="panel-head"><strong>{{ $account->name }}</strong></div>
        <div class="panel-body stack">
            @include('accounts.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('accounts.show', $account) }}">Cancel</a>
                <button class="btn" type="submit">Save Changes</button>
            </div>
        </div>
    </form>
@endsection
