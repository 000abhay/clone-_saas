<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\SupportTicket;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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

        $userMap = [];

        foreach ($users as $user) {
            $userMap[$user['email']] = User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'profile_summary' => $user['profile_summary'],
                    'status' => $user['status'],
                    'last_active_at' => $user['last_active_at'],
                    'password' => 'Password123!',
                ],
            );
        }

        $customers = [
            [
                'name' => 'Acme Corporation',
                'industry' => 'Technology',
                'location' => 'San Francisco, CA',
                'email' => 'contact@acme.com',
                'phone' => '+1 (555) 111-2222',
                'total_revenue' => 250000,
                'active_deals' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Tech Solutions Inc',
                'industry' => 'Software',
                'location' => 'New York, NY',
                'email' => 'info@techsol.com',
                'phone' => '+1 (555) 333-4444',
                'total_revenue' => 120000,
                'active_deals' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Digital Ventures',
                'industry' => 'Marketing',
                'location' => 'Austin, TX',
                'email' => 'hello@digitalventures.com',
                'phone' => '+1 (555) 555-6666',
                'total_revenue' => 85000,
                'active_deals' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Global Services Ltd',
                'industry' => 'Consulting',
                'location' => 'Chicago, IL',
                'email' => 'contact@globalservices.com',
                'phone' => '+1 (555) 777-8888',
                'total_revenue' => 180000,
                'active_deals' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Innovation Labs',
                'industry' => 'Research',
                'location' => 'Seattle, WA',
                'email' => 'team@innolabs.com',
                'phone' => '+1 (555) 345-6789',
                'total_revenue' => 156000,
                'active_deals' => 1,
                'status' => 'active',
            ],
        ];

        $customerMap = [];

        foreach ($customers as $customer) {
            $customerMap[$customer['name']] = Customer::updateOrCreate(
                ['email' => $customer['email']],
                $customer,
            );
        }

        $leads = [
            ['name' => 'John Smith', 'company' => 'Tech Solutions Inc', 'email' => 'john@techsol.com', 'phone' => '+1 (555) 123-4567', 'status' => 'Qualified', 'value' => 50000, 'contacted_at' => '2024-03-10', 'owner' => 'james.sales@codevocado.com'],
            ['name' => 'Sarah Johnson', 'company' => 'Digital Ventures', 'email' => 'sarah@digitalventures.com', 'phone' => '+1 (555) 234-5678', 'status' => 'New', 'value' => 35000, 'contacted_at' => '2024-03-12', 'owner' => 'emma.exec@codevocado.com'],
            ['name' => 'Mike Chen', 'company' => 'Innovation Labs', 'email' => 'mike@innolabs.com', 'phone' => '+1 (555) 345-6789', 'status' => 'Proposal', 'value' => 75000, 'contacted_at' => '2024-03-08', 'owner' => 'james.sales@codevocado.com'],
            ['name' => 'Lisa Anderson', 'company' => 'Global Services Ltd', 'email' => 'lisa@globalservices.com', 'phone' => '+1 (555) 456-7890', 'status' => 'Qualified', 'value' => 45000, 'contacted_at' => '2024-03-05', 'owner' => 'emma.exec@codevocado.com'],
        ];

        foreach ($leads as $lead) {
            Lead::updateOrCreate(
                ['email' => $lead['email']],
                [
                    'name' => $lead['name'],
                    'company' => $lead['company'],
                    'phone' => $lead['phone'],
                    'status' => $lead['status'],
                    'value' => $lead['value'],
                    'contacted_at' => $lead['contacted_at'],
                    'customer_id' => $customerMap[$lead['company']]->id ?? null,
                    'owner_id' => $userMap[$lead['owner']]->id ?? null,
                ],
            );
        }

        $deals = [
            ['title' => 'Acme Corp Enterprise Rollout', 'company' => 'Acme Corporation', 'stage' => 'prospect', 'value' => 50000],
            ['title' => 'Custom Development', 'company' => 'Innovation Labs', 'stage' => 'prospect', 'value' => 75000],
            ['title' => 'Tech Solutions Implementation', 'company' => 'Tech Solutions Inc', 'stage' => 'qualified', 'value' => 35000],
            ['title' => 'Cloud Infrastructure', 'company' => 'Digital Ventures', 'stage' => 'qualified', 'value' => 60000],
            ['title' => 'Digital Marketing Services', 'company' => 'Digital Ventures', 'stage' => 'proposal', 'value' => 25000],
            ['title' => 'Security Software License', 'company' => 'Global Services Ltd', 'stage' => 'negotiation', 'value' => 15000],
            ['title' => 'Global Transformation', 'company' => 'Global Services Ltd', 'stage' => 'closed_won', 'value' => 90000],
        ];

        foreach ($deals as $deal) {
            Deal::updateOrCreate(
                ['title' => $deal['title']],
                [
                    'company' => $deal['company'],
                    'stage' => $deal['stage'],
                    'value' => $deal['value'],
                    'customer_id' => $customerMap[$deal['company']]->id ?? null,
                ],
            );
        }

        $tasks = [
            ['title' => 'Follow-up with Acme Corp', 'description' => 'Check on proposal status', 'due_date' => '2024-03-18', 'priority' => 'high', 'task_status' => 'Pending', 'completed' => false, 'user' => 'james.sales@codevocado.com'],
            ['title' => 'Prepare presentation slides', 'description' => 'For Tech Solutions meeting', 'due_date' => '2024-03-20', 'priority' => 'medium', 'task_status' => 'In Progress', 'completed' => false, 'user' => 'sarah.admin@codevocado.com'],
            ['title' => 'Update CRM with new contacts', 'description' => 'Import leads from trade show', 'due_date' => '2024-03-19', 'priority' => 'medium', 'task_status' => 'Pending', 'completed' => false, 'user' => 'mike.manager@codevocado.com'],
            ['title' => 'Send contract to Digital Agency', 'description' => 'Final agreement for review', 'due_date' => '2024-03-17', 'priority' => 'high', 'task_status' => 'Completed', 'completed' => true, 'user' => 'emma.exec@codevocado.com'],
            ['title' => 'Schedule quarterly review', 'description' => 'With key customers', 'due_date' => '2024-03-25', 'priority' => 'low', 'task_status' => 'Pending', 'completed' => false, 'user' => 'james.sales@codevocado.com'],
        ];

        foreach ($tasks as $task) {
            Task::updateOrCreate(
                ['title' => $task['title']],
                [
                    'description' => $task['description'],
                    'due_date' => $task['due_date'],
                    'priority' => $task['priority'],
                    'task_status' => $task['task_status'],
                    'completed' => $task['completed'],
                    'assigned_user_id' => $userMap[$task['user']]->id ?? null,
                ],
            );
        }

        $tickets = [
            ['ticket_number' => 'TKT-001', 'title' => 'Integration issue with payment gateway', 'status' => 'Open', 'priority' => 'High', 'company' => 'Acme Corporation', 'user' => 'james.sales@codevocado.com', 'updated_label' => '2024-03-14'],
            ['ticket_number' => 'TKT-002', 'title' => 'Feature request: Export to CSV', 'status' => 'In-progress', 'priority' => 'Medium', 'company' => 'Tech Solutions Inc', 'user' => 'sarah.admin@codevocado.com', 'updated_label' => '2024-03-13'],
            ['ticket_number' => 'TKT-003', 'title' => 'Dashboard loading slowly', 'status' => 'Open', 'priority' => 'High', 'company' => 'Digital Ventures', 'user' => 'mike.manager@codevocado.com', 'updated_label' => '2024-03-14'],
            ['ticket_number' => 'TKT-004', 'title' => 'User unable to reset password', 'status' => 'Resolved', 'priority' => 'Medium', 'company' => 'Global Services Ltd', 'user' => 'emma.exec@codevocado.com', 'updated_label' => '2024-03-12'],
        ];

        foreach ($tickets as $ticket) {
            SupportTicket::updateOrCreate(
                ['ticket_number' => $ticket['ticket_number']],
                [
                    'title' => $ticket['title'],
                    'status' => $ticket['status'],
                    'priority' => $ticket['priority'],
                    'updated_label' => $ticket['updated_label'],
                    'customer_id' => $customerMap[$ticket['company']]->id ?? null,
                    'assignee_id' => $userMap[$ticket['user']]->id ?? null,
                ],
            );
        }
    }
}
