@extends('layouts.app')

@section('title', 'Edit Deal')
@section('page_title', 'Edit Deal')
@section('page_subtitle', 'Update pipeline position, forecast inputs, and linked records.')

@section('content')
    <form class="panel" method="POST" action="{{ route('deals.update', $deal) }}">
        @csrf
        @method('PATCH')
        <div class="panel-head"><strong>{{ $deal->title }}</strong></div>
        <div class="panel-body stack">
            @include('deals.form')
            <div class="footer-actions">
                <a class="btn-secondary" href="{{ route('deals.show', $deal) }}">Cancel</a>
                <button class="btn" type="submit">Save Changes</button>
            </div>
        </div>
    </form>
@endsection
