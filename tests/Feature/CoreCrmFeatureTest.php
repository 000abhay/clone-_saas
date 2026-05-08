<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\AuditLog;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\SupportTicket;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CoreCrmFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_and_password_request_page_are_available(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Sign In');

        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('Forgot your password?');
    }

    public function test_user_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create([
            'role' => 'super_admin',
            'password' => 'Password123!',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_account_lockout_is_applied_after_five_failed_attempts(): void
    {
        $user = User::factory()->create([
            'password' => 'Password123!',
        ]);

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'WrongPassword123',
            ])->assertSessionHasErrors('email');
        }

        $user->refresh();

        $this->assertEquals(5, $user->failed_login_attempts);
        $this->assertNotNull($user->locked_until);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'auth.failed',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_reset_flow_updates_the_user_password(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => 'Password123!',
        ]);

        $this->post('/forgot-password', [
            'email' => $user->email,
        ])->assertSessionHas('status');

        $token = null;

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ])->assertRedirect('/login');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'NewPassword123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_admin_can_create_team_members_and_sales_exec_cannot_access_team_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'password' => 'Password123!',
        ]);

        $this->actingAs($admin)->post('/team/members', [
            'name' => 'New Support User',
            'email' => 'new.support@example.com',
            'role' => 'support_agent',
            'profile_summary' => 'Customer support tickets',
            'status' => 'active',
            'password' => 'Password123!',
        ])->assertRedirect('/team');

        $this->assertDatabaseHas('users', [
            'email' => 'new.support@example.com',
            'role' => 'support_agent',
        ]);

        $salesExec = User::factory()->create([
            'role' => 'sales_exec',
        ]);

        $this->actingAs($salesExec)
            ->get('/team')
            ->assertForbidden();
    }

    public function test_leads_are_round_robin_assigned_and_duplicate_detection_blocks_reuse(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $firstOwner = User::factory()->create(['role' => 'sales_manager', 'name' => 'Owner One']);
        $secondOwner = User::factory()->create(['role' => 'sales_exec', 'name' => 'Owner Two']);

        $this->actingAs($admin)->post('/leads', [
            'first_name' => 'Priya',
            'last_name' => 'Nair',
            'company_name' => 'Green Horizon',
            'email' => 'priya@example.com',
            'phone' => '+1-555-111-2222',
            'source' => 'website',
            'status' => 'new',
        ])->assertRedirect();

        $this->actingAs($admin)->post('/leads', [
            'first_name' => 'Arjun',
            'last_name' => 'Mehta',
            'company_name' => 'Blue Peak',
            'email' => 'arjun@example.com',
            'phone' => '+1-555-222-3333',
            'source' => 'referral',
            'status' => 'working',
        ])->assertRedirect();

        $leadOne = Lead::where('email', 'priya@example.com')->firstOrFail();
        $leadTwo = Lead::where('email', 'arjun@example.com')->firstOrFail();

        $this->assertSame($firstOwner->id, $leadOne->assigned_user_id);
        $this->assertSame($secondOwner->id, $leadTwo->assigned_user_id);

        $this->actingAs($admin)->post('/leads', [
            'first_name' => 'Duplicate',
            'company_name' => 'Blue Peak',
            'email' => 'priya@example.com',
            'phone' => '+1-555-777-1111',
            'source' => 'manual',
            'status' => 'new',
        ])->assertSessionHasErrors('email');

        $this->assertEquals(2, Lead::count());
    }

    public function test_lead_conversion_creates_account_and_contact(): void
    {
        $manager = User::factory()->create(['role' => 'sales_manager']);
        $lead = Lead::create([
            'first_name' => 'Nina',
            'last_name' => 'Shah',
            'company_name' => 'North Ridge',
            'email' => 'nina@example.com',
            'phone' => '9999999999',
            'source' => 'manual',
            'status' => 'qualified',
            'score' => 70,
            'assigned_user_id' => $manager->id,
        ]);

        $this->actingAs($manager)->post("/leads/{$lead->id}/convert", [
            'account_name' => 'North Ridge',
            'contact_name' => 'Nina Shah',
            'lifecycle_stage' => 'customer',
        ])->assertRedirect();

        $lead->refresh();

        $this->assertSame('converted', $lead->status);
        $this->assertNotNull($lead->converted_at);
        $this->assertDatabaseHas('accounts', ['name' => 'North Ridge']);
        $this->assertDatabaseHas('contacts', [
            'lead_id' => $lead->id,
            'name' => 'Nina Shah',
        ]);
    }

    public function test_sales_exec_only_sees_and_updates_their_own_leads(): void
    {
        $salesExec = User::factory()->create(['role' => 'sales_exec']);
        $otherExec = User::factory()->create(['role' => 'sales_exec']);

        $ownLead = Lead::create([
            'first_name' => 'Owned',
            'email' => 'owned@example.com',
            'phone' => '1231231234',
            'source' => 'manual',
            'status' => 'new',
            'score' => 30,
            'assigned_user_id' => $salesExec->id,
        ]);

        $otherLead = Lead::create([
            'first_name' => 'Blocked',
            'email' => 'blocked@example.com',
            'phone' => '9879879876',
            'source' => 'manual',
            'status' => 'new',
            'score' => 30,
            'assigned_user_id' => $otherExec->id,
        ]);

        $this->actingAs($salesExec)
            ->get('/leads')
            ->assertOk()
            ->assertSee('Owned')
            ->assertDontSee('Blocked');

        $this->actingAs($salesExec)
            ->get("/leads/{$otherLead->id}")
            ->assertForbidden();

        $this->actingAs($salesExec)
            ->patch("/leads/{$ownLead->id}", [
                'first_name' => 'Owned',
                'email' => 'owned@example.com',
                'phone' => '1231231234',
                'source' => 'manual',
                'status' => 'working',
            ])
            ->assertRedirect();

        $ownLead->refresh();
        $this->assertSame('working', $ownLead->status);
    }

    public function test_pipeline_stage_update_changes_probability_and_writes_audit_log(): void
    {
        $manager = User::factory()->create(['role' => 'sales_manager']);
        $account = Account::create(['name' => 'Pipeline Labs', 'status' => 'active']);
        $prospect = PipelineStage::create(['name' => 'Prospect', 'order_column' => 1, 'probability' => 20, 'is_active' => true]);
        $proposal = PipelineStage::create(['name' => 'Proposal', 'order_column' => 2, 'probability' => 60, 'is_active' => true]);
        $deal = Deal::create([
            'account_id' => $account->id,
            'owner_id' => $manager->id,
            'stage_id' => $prospect->id,
            'title' => 'Pipeline Labs Rollout',
            'value' => 50000,
            'currency' => 'USD',
            'probability' => 20,
            'status' => 'open',
        ]);

        $this->actingAs($manager)
            ->patch("/deals/{$deal->id}/stage", ['stage_id' => $proposal->id])
            ->assertSessionHas('status');

        $deal->refresh();

        $this->assertSame($proposal->id, $deal->stage_id);
        $this->assertSame(60, $deal->probability);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => $deal->getMorphClass(),
            'auditable_id' => $deal->id,
            'action' => 'deal.stage_changed',
        ]);
    }

    public function test_tasks_can_be_linked_to_leads_and_marked_complete(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $owner = User::factory()->create(['role' => 'sales_exec']);
        $lead = Lead::create([
            'first_name' => 'Task',
            'last_name' => 'Target',
            'email' => 'task.target@example.com',
            'phone' => '1112223333',
            'source' => 'manual',
            'status' => 'new',
            'score' => 35,
        ]);

        $this->actingAs($admin)->post('/tasks', [
            'title' => 'Follow up call',
            'assigned_user_id' => $owner->id,
            'related_entity' => 'lead',
            'related_id' => $lead->id,
            'due_date' => now()->addDay()->toDateString(),
            'priority' => 'high',
            'status' => 'pending',
            'description' => 'Call the lead after demo signup.',
        ])->assertRedirect('/tasks');

        $task = Task::firstOrFail();
        $this->assertSame(Lead::class, $task->related_type);
        $this->assertSame($lead->id, $task->related_id);

        $this->actingAs($admin)->patch("/tasks/{$task->id}", [
            'title' => 'Follow up call',
            'assigned_user_id' => $owner->id,
            'related_entity' => 'lead',
            'related_id' => $lead->id,
            'due_date' => now()->addDay()->toDateString(),
            'priority' => 'high',
            'status' => 'completed',
            'description' => 'Completed',
        ])->assertRedirect('/tasks');

        $task->refresh();
        $this->assertNotNull($task->completed_at);
    }

    public function test_ticket_creation_sets_sla_and_scheduler_marks_breaches(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $agent = User::factory()->create(['role' => 'support_agent']);
        $account = Account::create(['name' => 'Support Labs', 'status' => 'active']);
        $contact = Contact::create(['account_id' => $account->id, 'name' => 'Mira Support', 'lifecycle_stage' => 'customer']);

        $this->actingAs($admin)->post('/tickets', [
            'account_id' => $account->id,
            'contact_id' => $contact->id,
            'assignee_id' => $agent->id,
            'subject' => 'P1 API outage',
            'description' => 'Callbacks are failing.',
            'priority' => 'high',
            'status' => 'open',
        ])->assertRedirect();

        $ticket = SupportTicket::firstOrFail();
        $this->assertNotNull($ticket->sla_response_due_at);
        $this->assertNotNull($ticket->sla_resolution_due_at);

        $ticket->forceFill([
            'sla_response_due_at' => now()->subHours(3),
            'sla_resolution_due_at' => now()->subHour(),
        ])->save();

        Artisan::call('tickets:evaluate-sla');

        $ticket->refresh();

        $this->assertNotNull($ticket->breached_at);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => $ticket->getMorphClass(),
            'auditable_id' => $ticket->id,
            'action' => 'ticket.sla_breached',
        ]);
    }

    public function test_reports_csv_export_uses_query_backed_data_and_sales_exec_cannot_open_support_queue(): void
    {
        $this->seed();

        $supportAgent = User::where('role', 'support_agent')->firstOrFail();
        $salesExec = User::where('role', 'sales_exec')->firstOrFail();

        $this->actingAs($supportAgent)
            ->get('/reports?format=csv')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->actingAs($supportAgent)
            ->get('/dashboard')
            ->assertSee('SLA Breaches')
            ->assertSee('Support Queue');

        $this->actingAs($salesExec)
            ->get('/tickets')
            ->assertForbidden();
    }
}
