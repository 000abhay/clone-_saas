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
}
