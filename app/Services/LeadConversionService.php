<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeadConversionService
{
    public function __construct(
        private readonly ActivityService $activityService,
        private readonly AuditService $auditService,
    ) {
    }

    public function convert(Lead $lead, User $actor, array $data = []): Contact
    {
        return DB::transaction(function () use ($lead, $actor, $data): Contact {
            $account = Account::query()->firstOrCreate(
                ['name' => $data['account_name'] ?: ($lead->company_name ?: $lead->full_name.' Account')],
                [
                    'industry' => $data['industry'] ?? null,
                    'owner_id' => $lead->assigned_user_id,
                    'status' => 'active',
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'notes' => $data['account_notes'] ?? $lead->notes,
                ],
            );

            $contact = Contact::query()->updateOrCreate(
                ['lead_id' => $lead->id],
                [
                    'account_id' => $account->id,
                    'owner_id' => $lead->assigned_user_id,
                    'name' => $data['contact_name'] ?: $lead->full_name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'job_title' => $data['job_title'] ?? null,
                    'lifecycle_stage' => $data['lifecycle_stage'] ?? 'customer',
                    'tags' => $data['tags'] ?? null,
                    'custom_fields' => $data['custom_fields'] ?? null,
                    'notes' => $data['contact_notes'] ?? $lead->notes,
                ],
            );

            $lead->forceFill([
                'status' => 'converted',
                'converted_at' => now(),
            ])->save();

            $this->activityService->record($lead, 'lead.converted', 'Lead converted to contact '.$contact->name, $actor, [
                'account_id' => $account->id,
                'contact_id' => $contact->id,
            ]);
            $this->activityService->record($contact, 'contact.created', 'Contact created from lead '.$lead->full_name, $actor);
            $this->activityService->record($account, 'account.updated', 'Account linked during lead conversion', $actor);
            $this->auditService->record('lead.converted', $actor, $lead, request()?->ip(), [
                'account_id' => $account->id,
                'contact_id' => $contact->id,
            ]);

            return $contact;
        });
    }
}
