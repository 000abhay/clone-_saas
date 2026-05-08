@extends('layouts.app')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Profile and password management for the current user.')

@section('content')
    <section class="grid cols-2">
        <form class="panel" method="POST" action="{{ route('settings.profile.update') }}">
            @csrf
            @method('PATCH')
            <div class="panel-head"><strong>Profile</strong></div>
            <div class="panel-body stack">
                <label class="field">
                    <span>Full Name</span>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                </label>
                <label class="field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                </label>
                <label class="field">
                    <span>Profile Summary</span>
                    <input type="text" name="profile_summary" value="{{ old('profile_summary', auth()->user()->profile_summary) }}">
                </label>
                <div class="footer-actions">
                    <button class="btn" type="submit">Save Profile</button>
                </div>
            </div>
        </form>

        <form class="panel" method="POST" action="{{ route('settings.password.update') }}">
            @csrf
            @method('PATCH')
            <div class="panel-head"><strong>Password</strong></div>
            <div class="panel-body stack">
                <label class="field">
                    <span>Current Password</span>
                    <input type="password" name="current_password" required>
                </label>
                <label class="field">
                    <span>New Password</span>
                    <input type="password" name="password" required>
                </label>
                <label class="field">
                    <span>Confirm New Password</span>
                    <input type="password" name="password_confirmation" required>
                </label>
                <div class="footer-actions">
                    <button class="btn" type="submit">Update Password</button>
                </div>
            </div>
        </form>
    </section>
@endsection
