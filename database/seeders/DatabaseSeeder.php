<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\SupportTicket;
use App\Models\Task;
use App\Models\User;
use App\Services\TicketSlaService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Sarah Admin',
                'email' => 'sarah.admin@codevocado.com',
                'role' => 'super_admin',
                'profile_summary' => 'Full platform access & analytics',
                'status' => 'active',
                'last_active_at' => now(),
            ],
            [
                'name' => 'Mike Manager',
                'email' => 'mike.manager@codevocado.com',
                'role' => 'admin',
                'profile_summary' => 'Company management & reporting',
                'status' => 'active',
                'last_active_at' => now()->subDay(),
            ],
            [
                'name' => 'James Sales',
                'email' => 'james.sales@codevocado.com',
                'role' => 'sales_manager',
                'profile_summary' => 'Team performance tracking',
                'status' => 'active',
                'last_active_at' => now(),
            ],
            [
                'name' => 'Emma Executive',
                'email' => 'emma.exec@codevocado.com',
                'role' => 'sales_exec',
                'profile_summary' => 'Lead & deal management',
                'status' => 'active',
                'last_active_at' => now()->subDays(2),
            ],
            [
                'name' => 'David Support',
                'email' => 'david.support@codevocado.com',
                'role' => 'support_agent',
                'profile_summary' => 'Customer support tickets',
                'status' => 'inactive',
                'last_active_at' => now()->subDays(5),
            ],
        ];

        $userMap = collect($users)->mapWithKeys(function (array $user) {
            $record = User::updateOrCreate(
                ['email' => $user['email']],
                array_merge($user, ['password' => 'Password123!']),
            );

            return [$user['email'] => $record];
        });

        $stageMap = collect([
            ['name' => 'Prospect', 'order_column' => 1, 'probability' => 20],
            ['name' => 'Qualified', 'order_column' => 2, 'probability' => 40],
            ['name' => 'Proposal', 'order_column' => 3, 'probability' => 60],
            ['name' => 'Negotiation', 'order_column' => 4, 'probability' => 80],
            ['name' => 'Closed Won', 'order_column' => 5, 'probability' => 100],
        ])->mapWithKeys(function (array $stage) {
            $record = PipelineStage::updateOrCreate(
                ['name' => $stage['name']],
                [
                    'order_column' => $stage['order_column'],
                    'probability' => $stage['probability'],
                    'required_fields' => ['title', 'value', 'owner_id'],
                    'is_active' => true,
                ],
            );

            return [strtolower(str_replace(' ', '_', $stage['name'])) => $record];
        });

        $accountMap = collect([
            [
                'name' => 'Acme Corporation',
                'industry' => 'Technology',
                'website' => 'https://acme.test',
                'email' => 'ops@acme.test',
                'phone' => '+1 (555) 111-2222',
                'location' => 'San Francisco, CA',
                'owner_id' => $userMap['james.sales@codevocado.com']->id,
                'status' => 'active',
                'notes' => 'Enterprise rollout target account.',
            ],
            [
                'name' => 'Tech Solutions Inc',
                'industry' => 'Software',
                'website' => 'https://techsolutions.test',
                'email' => 'hello@techsolutions.test',
                'phone' => '+1 (555) 333-4444',
                'location' => 'New York, NY',
                'owner_id' => $userMap['emma.exec@codevocado.com']->id,
                'status' => 'active',
                'notes' => 'Strong expansion signal from product team.',
            ],
            [
                'name' => 'Digital Ventures',
                'industry' => 'Marketing',
                'website' => 'https://digitalventures.test',
                'email' => 'team@digitalventures.test',
                'phone' => '+1 (555) 555-6666',
                'location' => 'Austin, TX',
                'owner_id' => $userMap['mike.manager@codevocado.com']->id,
                'status' => 'active',
                'notes' => 'High support activity and upsell opportunity.',
            ],
            [
                'name' => 'Innovation Labs',
                'industry' => 'Research',
                'website' => 'https://innovationlabs.test',
                'email' => 'contact@innovationlabs.test',
                'phone' => '+1 (555) 345-6789',
                'location' => 'Seattle, WA',
                'owner_id' => $userMap['james.sales@codevocado.com']->id,
                'status' => 'prospect',
                'notes' => 'Late-stage proposal in security tooling.',
            ],
        ])->mapWithKeys(function (array $account) {
            $record = Account::updateOrCreate(['name' => $account['name']], $account);

            return [$account['name'] => $record];
        });

        $leadMap = collect([
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'company_name' => 'Tech Solutions Inc',
                'email' => 'john@techsolutions.test',
                'phone' => '+1 (555) 123-4567',
                'source' => 'referral',
                'status' => 'qualified',
                'score' => 75,
                'assigned_user_id' => $userMap['james.sales@codevocado.com']->id,
                'last_contacted_at' => now()->subDays(2),
                'notes' => 'Requested implementation timeline and migration plan.',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'company_name' => 'Digital Ventures',
                'email' => 'sarah@digitalventures.test',
                'phone' => '+1 (555) 234-5678',
                'source' => 'website',
                'status' => 'new',
                'score' => 45,
                'assigned_user_id' => $userMap['emma.exec@codevocado.com']->id,
                'last_contacted_at' => now()->subDay(),
                'notes' => 'Inbound request from pricing page.',
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Nair',
                'company_name' => 'Green Horizon',
                'email' => 'priya@greenhorizon.test',
                'phone' => '+1 (555) 456-8899',
                'source' => 'campaign',
                'status' => 'working',
                'score' => 55,
                'assigned_user_id' => null,
                'last_contacted_at' => null,
                'notes' => 'Requires manual assignment for region coverage.',
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'company_name' => 'Acme Corporation',
                'email' => 'lisa@acme.test',
                'phone' => '+1 (555) 456-7890',
                'source' => 'manual',
                'status' => 'converted',
                'score' => 82,
                'assigned_user_id' => $userMap['emma.exec@codevocado.com']->id,
                'last_contacted_at' => now()->subDays(4),
                'converted_at' => now()->subDays(3),
                'notes' => 'Converted after discovery call and demo approval.',
            ],
        ])->mapWithKeys(function (array $lead) {
            $record = Lead::updateOrCreate(['email' => $lead['email']], $lead);

            return [$lead['email'] => $record];
        });

        $contactMap = collect([
            [
                'name' => 'John Smith',
                'account_id' => $accountMap['Tech Solutions Inc']->id,
                'lead_id' => $leadMap['john@techsolutions.test']->id,
                'owner_id' => $userMap['james.sales@codevocado.com']->id,
                'email' => 'john@techsolutions.test',
                'phone' => '+1 (555) 123-4567',
                'job_title' => 'VP Operations',
                'lifecycle_stage' => 'qualified',
                'tags' => ['forecast', 'north-america'],
                'custom_fields' => ['Preferred Channel' => 'Email'],
                'notes' => 'Main economic buyer for implementation.',
            ],
            [
                'name' => 'Lisa Anderson',
                'account_id' => $accountMap['Acme Corporation']->id,
                'lead_id' => $leadMap['lisa@acme.test']->id,
                'owner_id' => $userMap['emma.exec@codevocado.com']->id,
                'email' => 'lisa@acme.test',
                'phone' => '+1 (555) 456-7890',
                'job_title' => 'Revenue Operations Lead',
                'lifecycle_stage' => 'customer',
                'tags' => ['enterprise', 'champion'],
                'custom_fields' => ['Renewal Window' => 'Q4'],
                'notes' => 'Strong internal champion for rollout.',
            ],
            [
                'name' => 'Maya Chen',
                'account_id' => $accountMap['Innovation Labs']->id,
                'lead_id' => null,
                'owner_id' => $userMap['james.sales@codevocado.com']->id,
                'email' => 'maya@innovationlabs.test',
                'phone' => '+1 (555) 987-7788',
                'job_title' => 'CTO',
                'lifecycle_stage' => 'customer',
                'tags' => ['security', 'renewal'],
                'custom_fields' => ['Preferred Channel' => 'Phone'],
                'notes' => 'Decision maker for security budget.',
            ],
        ])->mapWithKeys(function (array $contact) {
            $record = Contact::updateOrCreate(['email' => $contact['email']], $contact);

            return [$contact['email'] => $record];
        });

        $dealMap = collect([
            [
                'title' => 'Acme Enterprise Rollout',
                'account_id' => $accountMap['Acme Corporation']->id,
                'contact_id' => $contactMap['lisa@acme.test']->id,
                'owner_id' => $userMap['emma.exec@codevocado.com']->id,
                'stage_id' => $stageMap['proposal']->id,
                'value' => 125000,
                'currency' => 'USD',
                'expected_close_date' => now()->addDays(18)->toDateString(),
                'probability' => 60,
                'status' => 'open',
                'is_forecastable' => true,
                'notes' => 'Awaiting procurement review.',
            ],
            [
                'title' => 'Tech Solutions Expansion',
                'account_id' => $accountMap['Tech Solutions Inc']->id,
                'contact_id' => $contactMap['john@techsolutions.test']->id,
                'owner_id' => $userMap['james.sales@codevocado.com']->id,
                'stage_id' => $stageMap['qualified']->id,
                'value' => 85000,
                'currency' => 'USD',
                'expected_close_date' => now()->addDays(30)->toDateString(),
                'probability' => 40,
                'status' => 'open',
                'is_forecastable' => true,
                'notes' => 'Scoping services package.',
            ],
            [
                'title' => 'Innovation Labs Security Suite',
                'account_id' => $accountMap['Innovation Labs']->id,
                'contact_id' => $contactMap['maya@innovationlabs.test']->id,
                'owner_id' => $userMap['james.sales@codevocado.com']->id,
                'stage_id' => $stageMap['negotiation']->id,
                'value' => 150000,
                'currency' => 'USD',
                'expected_close_date' => now()->addDays(10)->toDateString(),
                'probability' => 80,
                'status' => 'open',
                'is_forecastable' => true,
                'notes' => 'Security review complete, legal redlines pending.',
            ],
        ])->mapWithKeys(function (array $deal) {
            $record = Deal::updateOrCreate(['title' => $deal['title']], $deal);

            return [$deal['title'] => $record];
        });

        $ticketSlaService = app(TicketSlaService::class);

        $ticketMap = collect([
            [
                'subject' => 'Payment gateway callbacks failing',
                'account_id' => $accountMap['Acme Corporation']->id,
                'contact_id' => $contactMap['lisa@acme.test']->id,
                'assignee_id' => $userMap['david.support@codevocado.com']->id,
                'priority' => 'high',
                'status' => 'in_progress',
                'description' => 'Customer reports webhook retries and duplicate invoices.',
            ],
            [
                'subject' => 'CSV export formatting issue',
                'account_id' => $accountMap['Tech Solutions Inc']->id,
                'contact_id' => $contactMap['john@techsolutions.test']->id,
                'assignee_id' => $userMap['david.support@codevocado.com']->id,
                'priority' => 'medium',
                'status' => 'open',
                'description' => 'Exports need company and owner columns aligned.',
            ],
            [
                'subject' => 'Dashboard latency complaint',
                'account_id' => $accountMap['Digital Ventures']->id,
                'contact_id' => null,
                'assignee_id' => $userMap['david.support@codevocado.com']->id,
                'priority' => 'urgent',
                'status' => 'open',
                'description' => 'Dashboard is taking 12 seconds to load for the support team.',
            ],
        ])->mapWithKeys(function (array $ticket) use ($ticketSlaService) {
            $record = SupportTicket::create($ticket);
            $ticketSlaService->applyDeadlines($record);

            return [$record->subject => $record];
        });

        $ticketMap['Dashboard latency complaint']->forceFill([
            'sla_response_due_at' => now()->subHours(3),
            'sla_resolution_due_at' => now()->subHour(),
            'breached_at' => now()->subMinutes(45),
            'escalated_at' => now()->subMinutes(45),
        ])->save();

        Task::create([
            'related_type' => Lead::class,
            'related_id' => $leadMap['priya@greenhorizon.test']->id,
            'assigned_user_id' => $userMap['mike.manager@codevocado.com']->id,
            'title' => 'Assign Green Horizon lead',
            'description' => 'Review territory ownership and assign to a sales executive.',
            'due_date' => now()->addDay()->toDateString(),
            'priority' => 'high',
            'status' => 'pending',
        ]);

        Task::create([
            'related_type' => Deal::class,
            'related_id' => $dealMap['Innovation Labs Security Suite']->id,
            'assigned_user_id' => $userMap['james.sales@codevocado.com']->id,
            'title' => 'Send negotiation summary',
            'description' => 'Package security review notes and updated pricing.',
            'due_date' => now()->addDays(2)->toDateString(),
            'priority' => 'urgent',
            'status' => 'in_progress',
        ]);

        Task::create([
            'related_type' => SupportTicket::class,
            'related_id' => $ticketMap['Dashboard latency complaint']->id,
            'assigned_user_id' => $userMap['david.support@codevocado.com']->id,
            'title' => 'Collect performance traces',
            'description' => 'Capture timings from affected support accounts.',
            'due_date' => now()->subDay()->toDateString(),
            'priority' => 'high',
            'status' => 'pending',
        ]);

        Task::create([
            'related_type' => Contact::class,
            'related_id' => $contactMap['maya@innovationlabs.test']->id,
            'assigned_user_id' => $userMap['sarah.admin@codevocado.com']->id,
            'title' => 'Renewal prep brief',
            'description' => 'Summarize security utilization ahead of renewal.',
            'due_date' => now()->addWeek()->toDateString(),
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        $activityRows = [
            [$accountMap['Acme Corporation'], $userMap['sarah.admin@codevocado.com'], 'account.updated', 'Executive account flagged for rollout attention.'],
            [$leadMap['john@techsolutions.test'], $userMap['james.sales@codevocado.com'], 'lead.updated', 'Qualification complete and moved into forecast review.'],
            [$dealMap['Innovation Labs Security Suite'], $userMap['james.sales@codevocado.com'], 'deal.stage_changed', 'Moved into negotiation after technical sign-off.'],
            [$ticketMap['Dashboard latency complaint'], $userMap['david.support@codevocado.com'], 'ticket.sla_breached', 'Escalated after missing the urgent response target.'],
        ];

        foreach ($activityRows as [$subject, $user, $type, $description]) {
            Activity::create([
                'subject_type' => $subject->getMorphClass(),
                'subject_id' => $subject->id,
                'user_id' => $user->id,
                'type' => $type,
                'description' => $description,
            ]);
        }
    }
}
