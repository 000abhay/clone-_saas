@php($statuses = \App\Support\CrmOptions::accountStatuses())
<div class="field-grid">
    <label class="field">
        <span>Name</span>
        <input type="text" name="name" value="{{ old('name', $account->name) }}" required>
    </label>
    <label class="field">
        <span>Industry</span>
        <input type="text" name="industry" value="{{ old('industry', $account->industry) }}">
    </label>
    <label class="field">
        <span>Website</span>
        <input type="url" name="website" value="{{ old('website', $account->website) }}" placeholder="https://example.com">
    </label>
    <label class="field">
        <span>Email</span>
        <input type="email" name="email" value="{{ old('email', $account->email) }}">
    </label>
    <label class="field">
        <span>Phone</span>
        <input type="text" name="phone" value="{{ old('phone', $account->phone) }}">
    </label>
    <label class="field">
        <span>Location</span>
        <input type="text" name="location" value="{{ old('location', $account->location) }}">
    </label>
    <label class="field">
        <span>Owner</span>
        <select name="owner_id">
            <option value="">Unassigned</option>
            @foreach ($owners as $owner)
                <option value="{{ $owner->id }}" @selected(old('owner_id', $account->owner_id) == $owner->id)>{{ $owner->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Status</span>
        <select name="status" required>
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $account->status ?: 'active') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field full">
        <span>Notes</span>
        <textarea name="notes">{{ old('notes', $account->notes) }}</textarea>
    </label>
</div>
