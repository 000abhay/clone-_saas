@extends('layouts.app')

@section('title', 'New Deal')
@section('page_title', 'Create Deal')
@section('page_subtitle', 'Add a pipeline opportunity with stage, owner, and forecast inputs.')

@section('content')
    <form class="panel" method="POST" action="{{ route('deals.store') }}">
        @csrf
        <div class="panel-head"><strong>Deal Details</strong></div>
        <div class="panel-body stack">
            @include('deals.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('deals.index') }}">Cancel</a>
                <button class="btn" type="submit">Create Deal</button>
            </div>
        </div>
    </form>
@endsection
