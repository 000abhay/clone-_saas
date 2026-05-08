<?php

namespace App\Http\Requests\Leads;

use App\Models\Lead;
use App\Support\CrmOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'source' => ['required', Rule::in(array_keys(CrmOptions::leadSources()))],
            'status' => ['required', Rule::in(array_keys(CrmOptions::leadStatuses()))],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $leadId = $this->route('lead')?->id;

            if (! $this->filled('email') && ! $this->filled('phone')) {
                $validator->errors()->add('email', 'At least an email or phone number is required.');

                return;
            }

            $duplicate = Lead::query()
                ->when($leadId, fn ($query) => $query->whereKeyNot($leadId))
                ->where(function ($query): void {
                    if ($this->filled('email')) {
                        $query->orWhere('email', $this->string('email')->toString());
                    }

                    if ($this->filled('phone')) {
                        $query->orWhere('phone', $this->string('phone')->toString());
                    }
                })
                ->exists();

            if ($duplicate) {
                $validator->errors()->add('email', 'A lead with the same email or phone already exists.');
            }
        });
    }
}
