@php($statuses = \App\Support\CrmOptions::dealStatuses())
<div class="field-grid">
    <label class="field">
        <span>Title</span>
        <input type="text" name="title" value="{{ old('title', $deal->title) }}" required>
    </label>
    <label class="field">
        <span>Account</span>
        <select name="account_id">
            <option value="">No linked account</option>
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}" @selected(old('account_id', $deal->account_id) == $account->id)>{{ $account->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Contact</span>
        <select name="contact_id">
            <option value="">No linked contact</option>
            @foreach ($contacts as $contact)
                <option value="{{ $contact->id }}" @selected(old('contact_id', $deal->contact_id) == $contact->id)>{{ $contact->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Owner</span>
        <select name="owner_id">
            <option value="">Unassigned</option>
            @foreach ($owners as $owner)
                <option value="{{ $owner->id }}" @selected(old('owner_id', $deal->owner_id) == $owner->id)>{{ $owner->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Stage</span>
        <select name="stage_id" required>
            @foreach ($stages as $stage)
                <option value="{{ $stage->id }}" @selected(old('stage_id', $deal->stage_id ?: optional($stages->first())->id) == $stage->id)>{{ $stage->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Status</span>
        <select name="status">
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $deal->status ?: 'open') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Value</span>
        <input type="number" step="0.01" name="value" value="{{ old('value', $deal->value) }}" required>
    </label>
    <label class="field">
        <span>Currency</span>
        <input type="text" name="currency" value="{{ old('currency', $deal->currency ?: 'USD') }}" maxlength="3" required>
    </label>
    <label class="field">
        <span>Expected Close Date</span>
        <input type="date" name="expected_close_date" value="{{ old('expected_close_date', optional($deal->expected_close_date)->toDateString()) }}">
    </label>
    <label class="field">
        <span>Probability (%)</span>
        <input type="number" name="probability" min="0" max="100" value="{{ old('probability', $deal->probability ?: optional($stages->first())->probability ?: 0) }}" required>
    </label>
    <label class="field full">
        <span>Forecastable</span>
        <select name="is_forecastable">
            <option value="1" @selected(old('is_forecastable', $deal->is_forecastable ?? true))>Yes</option>
            <option value="0" @selected(! old('is_forecastable', $deal->is_forecastable ?? true))>No</option>
        </select>
    </label>
    <label class="field full">
        <span>Notes</span>
        <textarea name="notes">{{ old('notes', $deal->notes) }}</textarea>
    </label>
</div>
