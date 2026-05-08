@php
    $stages = \App\Support\CrmOptions::contactLifecycleStages();
    $customFieldText = old('custom_fields');
    if ($customFieldText === null && is_array($contact->custom_fields ?? null)) {
        $customFieldText = collect($contact->custom_fields)->map(fn ($value, $key) => $key.': '.$value)->implode("\n");
    }
@endphp
<div class="field-grid">
    <label class="field">
        <span>Name</span>
        <input type="text" name="name" value="{{ old('name', $contact->name) }}" required>
    </label>
    <label class="field">
        <span>Account</span>
        <select name="account_id">
            <option value="">No linked account</option>
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}" @selected(old('account_id', $contact->account_id) == $account->id)>{{ $account->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Email</span>
        <input type="email" name="email" value="{{ old('email', $contact->email) }}">
    </label>
    <label class="field">
        <span>Phone</span>
        <input type="text" name="phone" value="{{ old('phone', $contact->phone) }}">
    </label>
    <label class="field">
        <span>Job Title</span>
        <input type="text" name="job_title" value="{{ old('job_title', $contact->job_title) }}">
    </label>
    <label class="field">
        <span>Owner</span>
        <select name="owner_id">
            <option value="">Unassigned</option>
            @foreach ($owners as $owner)
                <option value="{{ $owner->id }}" @selected(old('owner_id', $contact->owner_id) == $owner->id)>{{ $owner->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Lifecycle Stage</span>
        <select name="lifecycle_stage">
            @foreach ($stages as $key => $label)
                <option value="{{ $key }}" @selected(old('lifecycle_stage', $contact->lifecycle_stage ?: 'customer') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Tags</span>
        <input type="text" name="tags" value="{{ old('tags', is_array($contact->tags ?? null) ? implode(', ', $contact->tags) : null) }}" placeholder="vip, renewal, north-america">
    </label>
    <label class="field full">
        <span>Custom Fields</span>
        <textarea name="custom_fields" placeholder="Preferred Channel: WhatsApp&#10;Renewal Window: Q4">{{ $customFieldText }}</textarea>
    </label>
    <label class="field full">
        <span>Notes</span>
        <textarea name="notes">{{ old('notes', $contact->notes) }}</textarea>
    </label>
</div>
