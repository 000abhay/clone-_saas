<?php

namespace App\Http\Requests\Tickets;

use App\Support\CrmOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
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
            'assignee_id' => ['nullable', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(array_keys(CrmOptions::ticketPriorities()))],
            'status' => ['required', Rule::in(array_keys(CrmOptions::ticketStatuses()))],
        ];
    }
}
