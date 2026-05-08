@php
    $currentRelatedType = old('related_entity');
    if (! $currentRelatedType && $task->related_type) {
        $currentRelatedType = array_search($task->related_type, \App\Support\CrmOptions::taskRelatedTypes(), true);
    }
@endphp
<div class="field-grid">
    <label class="field">
        <span>Title</span>
        <input type="text" name="title" value="{{ old('title', $task->title) }}" required>
    </label>
    <label class="field">
        <span>Assigned User</span>
        <select name="assigned_user_id">
            <option value="">Unassigned</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected(old('assigned_user_id', $task->assigned_user_id) == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Related Record Type</span>
        <select name="related_entity" id="related_entity">
            <option value="">No linked record</option>
            @foreach ($relatedOptions as $type => $items)
                <option value="{{ $type }}" @selected($currentRelatedType === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Related Record</span>
        <select name="related_id" id="related_id">
            <option value="">Select a record</option>
        </select>
    </label>
    <label class="field">
        <span>Due Date</span>
        <input type="date" name="due_date" value="{{ old('due_date', optional($task->due_date)->toDateString()) }}">
    </label>
    <label class="field">
        <span>Priority</span>
        <select name="priority">
            @foreach (\App\Support\CrmOptions::taskPriorities() as $key => $label)
                <option value="{{ $key }}" @selected(old('priority', $task->priority ?: 'medium') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field full">
        <span>Status</span>
        <select name="status">
            @foreach (\App\Support\CrmOptions::taskStatuses() as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $task->status ?: 'pending') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field full">
        <span>Description</span>
        <textarea name="description">{{ old('description', $task->description) }}</textarea>
    </label>
</div>

@push('scripts')
<script>
    (() => {
        const relatedOptions = @json($relatedOptions);
        const entityField = document.getElementById('related_entity');
        const idField = document.getElementById('related_id');
        const currentId = '{{ old('related_id', $task->related_id) }}';

        function renderOptions() {
            const type = entityField.value;
            idField.innerHTML = '<option value="">Select a record</option>';
            (relatedOptions[type] || []).forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.label;
                if (String(item.id) === String(currentId)) {
                    option.selected = true;
                }
                idField.appendChild(option);
            });
        }

        entityField.addEventListener('change', renderOptions);
        renderOptions();
    })();
</script>
@endpush
