@extends('layouts.app')

@section('title', $deal->title)
@section('page_title', $deal->title)
@section('page_subtitle', 'Deal overview with stage changes and CRM activity history.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('deals.edit', $deal) }}">Edit Deal</a>
        <a class="btn-link" href="{{ route('pipeline.index') }}">Open Pipeline</a>
    </div>
@endsection

@section('content')
    <section class="split">
        <article class="card stack">
            <div><strong>Account:</strong> {{ $deal->account?->name ?? 'No account' }}</div>
            <div><strong>Contact:</strong> {{ $deal->contact?->name ?? 'No contact' }}</div>
            <div><strong>Owner:</strong> {{ $deal->owner?->name ?? 'Unassigned' }}</div>
            <div><strong>Stage:</strong> <span class="badge blue">{{ $deal->stage?->name }}</span></div>
            <div><strong>Status:</strong> {{ ucfirst($deal->status) }}</div>
            <div><strong>Value:</strong> ${{ number_format((float) $deal->value, 0) }} {{ $deal->currency }}</div>
            <div><strong>Probability:</strong> {{ $deal->probability }}%</div>
            <div><strong>Forecastable:</strong> {{ $deal->is_forecastable ? 'Yes' : 'No' }}</div>
            <form method="POST" action="{{ route('deals.stage.update', $deal) }}" class="stack" style="margin-top:10px;">
                @csrf
                @method('PATCH')
                <label class="field">
                    <span>Move Stage</span>
                    <select name="stage_id">
                        @foreach (\App\Models\PipelineStage::where('is_active', true)->orderBy('order_column')->get() as $stage)
                            <option value="{{ $stage->id }}" @selected($deal->stage_id === $stage->id)>{{ $stage->name }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="btn" type="submit">Update Stage</button>
            </form>
        </article>
        <article class="panel">
            <div class="panel-head"><strong>Activity Timeline</strong></div>
            <div class="panel-body">
                @include('partials.activity-list', ['activities' => $deal->activities])
            </div>
        </article>
    </section>
@endsection
