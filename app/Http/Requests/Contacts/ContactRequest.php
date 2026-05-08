<?php

namespace App\Http\Requests\Contacts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['nullable', 'exists:accounts,id'],
            'lead_id' => ['nullable', 'exists:leads,id'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'lifecycle_stage' => ['required', Rule::in(array_keys(\App\Support\CrmOptions::contactLifecycleStages()))],
            'tags' => ['nullable', 'string'],
            'custom_fields' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
