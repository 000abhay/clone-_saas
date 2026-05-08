@extends('layouts.app')

@section('title', 'Pipeline')
@section('page_title', 'Pipeline')
@section('page_subtitle', 'Drag deals between stages or use the fallback selector on each card.')
@section('actions')
    <div class="actions">
        <a class="btn-secondary" href="{{ route('deals.index') }}">Table View</a>
        <a class="btn" href="{{ route('deals.create') }}">New Deal</a>
    </div>
@endsection

@section('content')
    <section class="kanban">
        @foreach ($stages as $stage)
            <article class="kanban-col" data-stage-id="{{ $stage->id }}">
                <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom:14px;">
                    <strong>{{ $stage->name }}</strong>
                    <span class="badge blue">{{ $stage->deals->count() }}</span>
                </div>
                @foreach ($stage->deals as $deal)
                    <div class="kanban-card" draggable="true" data-deal-id="{{ $deal->id }}">
                        <strong>{{ $deal->title }}</strong>
                        <div style="margin-top:8px;">{{ $deal->account?->name ?? 'No account linked' }}</div>
                        <div class="muted" style="margin-top:6px;">${{ number_format((float) $deal->value, 0) }} · {{ $deal->owner?->name ?? 'Unassigned' }}</div>
                        <form method="POST" action="{{ route('deals.stage.update', $deal) }}" style="margin-top:12px;">
                            @csrf
                            @method('PATCH')
                            <select name="stage_id" onchange="this.form.submit()">
                                @foreach ($stages as $option)
                                    <option value="{{ $option->id }}" @selected($stage->id === $option->id)>{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endforeach
            </article>
        @endforeach
    </section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.kanban-card').forEach((card) => {
        card.addEventListener('dragstart', (event) => {
            event.dataTransfer.setData('text/plain', card.dataset.dealId);
        });
    });

    document.querySelectorAll('.kanban-col').forEach((column) => {
        column.addEventListener('dragover', (event) => event.preventDefault());
        column.addEventListener('drop', async (event) => {
            event.preventDefault();
            const dealId = event.dataTransfer.getData('text/plain');
            const stageId = column.dataset.stageId;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            await fetch(`{{ url('/deals') }}/${dealId}/stage`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-HTTP-Method-Override': 'PATCH',
                },
                body: new URLSearchParams({ stage_id: stageId }),
            });

            window.location.reload();
        });
    });
</script>
@endpush
