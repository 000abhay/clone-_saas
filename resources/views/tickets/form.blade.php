@php
    $priorities = \App\Support\CrmOptions::ticketPriorities();
    $statuses = \App\Support\CrmOptions::ticketStatuses();
@endphp
<div class="field-grid">
    <label class="field">
        <span>Subject</span>
        <input type="text" name="subject" value="{{ old('subject', $ticket->subject) }}" required>
    </label>
    <label class="field">
        <span>Account</span>
        <select name="account_id">
            <option value="">No linked account</option>
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}" @selected(old('account_id', $ticket->account_id) == $account->id)>{{ $account->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Contact</span>
        <select name="contact_id">
            <option value="">No linked contact</option>
            @foreach ($contacts as $contact)
                <option value="{{ $contact->id }}" @selected(old('contact_id', $ticket->contact_id) == $contact->id)>{{ $contact->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Assignee</span>
        <select name="assignee_id">
            <option value="">Unassigned</option>
            @foreach ($assignees as $assignee)
                <option value="{{ $assignee->id }}" @selected(old('assignee_id', $ticket->assignee_id) == $assignee->id)>{{ $assignee->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Priority</span>
        <select name="priority">
            @foreach ($priorities as $key => $label)
                <option value="{{ $key }}" @selected(old('priority', $ticket->priority ?: 'medium') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Status</span>
        <select name="status">
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $ticket->status ?: 'open') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field full">
        <span>Description</span>
        <textarea name="description">{{ old('description', $ticket->description) }}</textarea>
    </label>
</div>
