<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sign In');
    }

    public function test_a_user_can_log_in_and_reach_the_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Sarah Admin',
            'email' => 'sarah.admin@codevocado.com',
            'role' => 'super_admin',
            'profile_summary' => 'Full platform access & analytics',
            'password' => 'Password123!',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Sarah Admin')
            ->assertSee('SUPER ADMIN');
    }

    public function test_admin_can_add_a_team_member(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post('/team/members', [
            'name' => 'New Support User',
            'email' => 'new.support@example.com',
            'role' => 'support_agent',
            'profile_summary' => 'Customer support tickets',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/team');

        $this->assertDatabaseHas('users', [
            'email' => 'new.support@example.com',
            'role' => 'support_agent',
        ]);
    }

    public function test_mike_manager_can_log_in_and_see_the_same_crm_shell(): void
    {
        $user = User::factory()->create([
            'name' => 'Mike Manager',
            'email' => 'mike.manager@codevocado.com',
            'role' => 'admin',
            'profile_summary' => 'Company management & reporting',
            'password' => 'Password123!',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(302);
        $response->assertHeader('Location', '/dashboard');

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Mike Manager')
            ->assertSee('Company management &amp; reporting', false)
            ->assertSee('ADMIN')
            ->assertSee('Dashboard')
            ->assertSee('Leads')
            ->assertSee('Pipeline')
            ->assertSee('Tasks')
            ->assertSee('Reports')
            ->assertSee('Billing')
            ->assertSee('Integrations')
            ->assertSee('Settings')
            ->assertDontSee('Customers')
            ->assertDontSee('Support')
            ->assertDontSee('Team')
            ;
    }

    public function test_mike_manager_is_redirected_away_from_hidden_pages(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => 'Password123!',
        ]);

        $this->actingAs($user)
            ->get('/customers')
            ->assertRedirect('/dashboard');
    }

    public function test_james_sales_sees_sales_manager_navigation_and_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'James Sales',
            'email' => 'james.sales@codevocado.com',
            'role' => 'sales_manager',
            'profile_summary' => 'Team performance tracking',
            'password' => 'Password123!',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('James Sales')
            ->assertSee('SALES MANAGER')
            ->assertSee('Leads')
            ->assertSee('Pipeline')
            ->assertSee('Tasks')
            ->assertSee('Reports')
            ->assertSee('Billing')
            ->assertSee('Integrations')
            ->assertSee('Settings')
            ->assertDontSee('Customers')
            ->assertDontSee('Support')
            ->assertSee('Team Revenue Performance')
            ->assertSee('Team Member Performance');
    }

    public function test_emma_executive_sees_the_sales_exec_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Emma Executive',
            'email' => 'emma.exec@codevocado.com',
            'role' => 'sales_exec',
            'profile_summary' => 'Lead & deal management',
            'password' => 'Password123!',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Emma Executive')
            ->assertSee('Lead &amp; deal management', false)
            ->assertSee('SALES EXECUTIVE')
            ->assertSee('Dashboard')
            ->assertSee('Leads')
            ->assertSee('Pipeline')
            ->assertSee('Tasks')
            ->assertSee('Billing')
            ->assertSee('Integrations')
            ->assertSee('Settings')
            ->assertDontSee('Customers')
            ->assertDontSee('Support')
            ->assertSee('Revenue Trend')
            ->assertSee('Lead Sources')
            ->assertSee('Sales Funnel')
            ->assertSee('Recent Activity');
    }
}
