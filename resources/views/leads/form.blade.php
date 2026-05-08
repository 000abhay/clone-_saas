@php
    $statuses = \App\Support\CrmOptions::leadStatuses();
    $sources = \App\Support\CrmOptions::leadSources();
@endphp
<div class="field-grid">
    <label class="field">
        <span>First Name</span>
        <input type="text" name="first_name" value="{{ old('first_name', $lead->first_name) }}" required>
    </label>
    <label class="field">
        <span>Last Name</span>
        <input type="text" name="last_name" value="{{ old('last_name', $lead->last_name) }}">
    </label>
    <label class="field">
        <span>Company Name</span>
        <input type="text" name="company_name" value="{{ old('company_name', $lead->company_name) }}">
    </label>
    <label class="field">
        <span>Email</span>
        <input type="email" name="email" value="{{ old('email', $lead->email) }}">
    </label>
    <label class="field">
        <span>Phone</span>
        <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}">
    </label>
    <label class="field">
        <span>Source</span>
        <select name="source">
            @foreach ($sources as $key => $label)
                <option value="{{ $key }}" @selected(old('source', $lead->source ?: 'manual') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Status</span>
        <select name="status">
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $lead->status ?: 'new') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Assigned To</span>
        <select name="assigned_user_id">
            <option value="">Auto-assign with round robin</option>
            @foreach ($owners as $owner)
                <option value="{{ $owner->id }}" @selected(old('assigned_user_id', $lead->assigned_user_id) == $owner->id)>{{ $owner->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field full">
        <span>Notes</span>
        <textarea name="notes">{{ old('notes', $lead->notes) }}</textarea>
    </label>
</div>
