<?php

namespace App\Http\Requests\Tasks;

use App\Support\CrmOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'related_entity' => ['nullable', Rule::in(array_keys(CrmOptions::taskRelatedTypes()))],
            'related_id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', Rule::in(array_keys(CrmOptions::taskPriorities()))],
            'status' => ['required', Rule::in(array_keys(CrmOptions::taskStatuses()))],
        ];
    }
}
