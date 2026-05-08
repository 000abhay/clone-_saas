<div class="timeline">
    @forelse ($activities as $activity)
        <article class="timeline-item">
            <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start;">
                <div>
                    <strong>{{ \Illuminate\Support\Str::headline(str_replace('.', ' ', $activity->type)) }}</strong>
                    <div style="margin-top:6px;">{{ $activity->description }}</div>
                </div>
                <div class="muted" style="text-align:right;">
                    <div>{{ $activity->user?->name ?? 'System' }}</div>
                    <div>{{ $activity->created_at?->diffForHumans() }}</div>
                </div>
            </div>
        </article>
    @empty
        <div class="note">No activity has been recorded for this record yet.</div>
    @endforelse
</div>
