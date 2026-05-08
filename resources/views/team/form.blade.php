@php($roles = \App\Support\CrmOptions::roles())
<div class="field-grid">
    <label class="field">
        <span>Name</span>
        <input type="text" name="name" value="{{ old('name', $member->name) }}" required>
    </label>
    <label class="field">
        <span>Email</span>
        <input type="email" name="email" value="{{ old('email', $member->email) }}" required>
    </label>
    <label class="field">
        <span>Role</span>
        <select name="role">
            @foreach ($roles as $key => $label)
                <option value="{{ $key }}" @selected(old('role', $member->role ?: 'sales_exec') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    <label class="field">
        <span>Status</span>
        <select name="status">
            <option value="active" @selected(old('status', $member->status ?: 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $member->status) === 'inactive')>Inactive</option>
        </select>
    </label>
    <label class="field full">
        <span>Profile Summary</span>
        <input type="text" name="profile_summary" value="{{ old('profile_summary', $member->profile_summary) }}">
    </label>
    <label class="field full">
        <span>Password {{ $member->exists ? '(leave blank to keep current password)' : '' }}</span>
        <input type="password" name="password" {{ $member->exists ? '' : 'required' }}>
    </label>
</div>
