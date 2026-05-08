<?php

namespace App\Http\Requests\Leads;

use App\Support\CrmOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadConvertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'lifecycle_stage' => ['nullable', Rule::in(array_keys(CrmOptions::contactLifecycleStages()))],
            'tags' => ['nullable', 'string'],
        ];
    }
}
