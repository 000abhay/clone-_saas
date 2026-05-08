<?php

namespace App\Http\Requests\Deals;

use App\Support\CrmOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['nullable', 'exists:accounts,id'],
            'contact_id' => ['nullable', 'exists:contacts,id'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'stage_id' => ['required', 'exists:pipeline_stages,id'],
            'title' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'expected_close_date' => ['nullable', 'date'],
            'probability' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(array_keys(CrmOptions::dealStatuses()))],
            'is_forecastable' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
