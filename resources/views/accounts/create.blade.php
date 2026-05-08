@extends('layouts.app')

@section('title', 'New Account')
@section('page_title', 'Create Account')
@section('page_subtitle', 'Capture a company record and assign ownership.')

@section('content')
    <form class="panel" method="POST" action="{{ route('accounts.store') }}">
        @csrf
        <div class="panel-head"><strong>Account Details</strong></div>
        <div class="panel-body stack">
            @include('accounts.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('accounts.index') }}">Cancel</a>
                <button class="btn" type="submit">Create Account</button>
            </div>
        </div>
    </form>
@endsection
