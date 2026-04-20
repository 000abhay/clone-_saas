<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\SupportTicket;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CrmController extends Controller
{
    public function dashboard(): View
    {
        return $this->page('dashboard');
    }

    public function leads(): View
    {
        return $this->page('leads');
    }

    public function customers(): View
    {
        return $this->page('customers');
    }

    public function pipeline(): View
    {
        return $this->page('pipeline');
    }

    public function tasks(): View
    {
        return $this->page('tasks');
    }

    public function support(): View
    {
        return $this->page('support');
    }

    public function reports(): View
    {
        return $this->page('reports');
    }

    public function team(): View
    {
        return $this->page('team');
    }

    public function billing(): View
    {
        return $this->page('billing');
    }

    public function integrations(): View
    {
        return $this->page('integrations');
    }

    public function settings(): View
    {
        return $this->page('settings');
    }

    public function storeMember(Request $request): RedirectResponse|Response
    {
        abort_unless($request->user()->canManageTeam(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:super_admin,admin,sales_manager,sales_exec,support_agent'],
            'profile_summary' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'profile_summary' => $validated['profile_summary'],
            'status' => 'active',
            'last_active_at' => now(),
            'password' => $validated['password'],
        ]);

        return redirect('/team')->with('status', 'Team member added successfully.');
    }

    private function page(string $section): View
    {
        $user = auth()->user();
        $allowedSections = $this->allowedSectionsFor($user);

        if (! in_array($section, $allowedSections, true)) {
            throw new HttpResponseException(
                redirect('/'.$allowedSections[0])
            );
        }

        $customers = Customer::orderBy('name')->get();
        $teamMembers = User::orderByDesc('last_active_at')->orderBy('name')->get();
        $leads = Lead::with(['customer', 'owner'])->orderByDesc('value')->get();
        $deals = Deal::with('customer')->orderByDesc('value')->get();
        $tasks = Task::with('assignedUser')->orderBy('due_date')->get();
        $tickets = SupportTicket::with(['customer', 'assignee'])->orderBy('ticket_number')->get();

        $monthlyRevenue = [45000, 52000, 48000, 61000, 58000, 68000];
        $userGrowth = [1200, 1450, 1800, 2010, 2240, 2580];
        $companyGrowth = [22, 28, 34, 46, 55, 120];
        $regions = [
            ['label' => 'North America', 'value' => 45, 'color' => '#7bb8e6'],
            ['label' => 'Europe', 'value' => 28, 'color' => '#7a2de2'],
            ['label' => 'Asia Pacific', 'value' => 18, 'color' => '#3b82f6'],
            ['label' => 'Other', 'value' => 9, 'color' => '#06b6d4'],
        ];

        $stats = [
            ['label' => 'Total Users', 'value' => number_format($teamMembers->count()), 'change' => '+12%', 'icon' => 'users'],
            ['label' => 'Active Companies', 'value' => number_format($customers->where('status', 'active')->count()), 'change' => '+8%', 'icon' => 'company'],
            ['label' => 'System Health', 'value' => '99.9%', 'change' => 'up', 'icon' => 'server'],
            ['label' => 'API Calls (24h)', 'value' => '45.2M', 'change' => '+23%', 'icon' => 'pulse'],
        ];

        if ($user->role === 'sales_manager') {
            $stats = [
                ['label' => 'Team Revenue (MTD)', 'value' => '$450K', 'change' => '', 'icon' => 'trend'],
                ['label' => 'Team Members', 'value' => '8', 'change' => '', 'icon' => 'users'],
                ['label' => 'Deals Closed', 'value' => '12', 'change' => '', 'icon' => 'target'],
                ['label' => 'Win Rate', 'value' => '65%', 'change' => '', 'icon' => 'award'],
            ];
        }

        if ($user->role === 'sales_exec') {
            $stats = [
                ['label' => 'Total Leads', 'value' => '2,543', 'change' => '+12%', 'icon' => 'users'],
                ['label' => 'Active Deals', 'value' => '156', 'change' => '+8%', 'icon' => 'target'],
                ['label' => 'Revenue (MTD)', 'value' => '$67K', 'change' => '+15%', 'icon' => 'revenue'],
                ['label' => 'Win Rate', 'value' => '42%', 'change' => '+3%', 'icon' => 'trend'],
            ];
        }

        $alerts = [
            ['title' => 'High API Usage', 'detail' => 'API usage at 85% of quota', 'tone' => 'amber'],
            ['title' => 'Server Maintenance', 'detail' => 'Scheduled for Sunday 2AM UTC', 'tone' => 'blue'],
            ['title' => 'Security Audit', 'detail' => 'Quarterly security audit passed', 'tone' => 'green'],
        ];

        $billingPlans = [
            [
                'name' => 'Free',
                'subtitle' => 'For getting started',
                'price' => '$0',
                'period' => '/month',
                'button' => 'Upgrade',
                'features' => ['Up to 10 leads', 'Basic CRM', 'Email support'],
                'featured' => false,
                'current' => false,
            ],
            [
                'name' => 'Starter',
                'subtitle' => 'For small teams',
                'price' => '$29',
                'period' => '/month',
                'button' => 'Upgrade',
                'features' => ['Up to 100 leads', 'Advanced CRM', 'Priority support', 'Custom fields'],
                'featured' => false,
                'current' => false,
            ],
            [
                'name' => 'Professional',
                'subtitle' => 'For growing teams',
                'price' => '$99',
                'period' => '/month',
                'button' => 'Current Plan',
                'features' => ['Unlimited leads', 'Full CRM suite', '24/7 support', 'API access', 'Custom integrations'],
                'featured' => true,
                'current' => true,
            ],
            [
                'name' => 'Enterprise',
                'subtitle' => 'For enterprises',
                'price' => 'Custom',
                'period' => '',
                'button' => 'Upgrade',
                'features' => ['Custom everything', 'Dedicated support', 'SLA guarantee', 'Advanced security'],
                'featured' => false,
                'current' => false,
            ],
        ];

        $billingHistory = [
            ['invoice' => 'INV-001', 'date' => '2024-03-01', 'amount' => '$99.00', 'status' => 'Paid'],
            ['invoice' => 'INV-002', 'date' => '2024-02-01', 'amount' => '$99.00', 'status' => 'Paid'],
            ['invoice' => 'INV-003', 'date' => '2024-01-01', 'amount' => '$99.00', 'status' => 'Paid'],
        ];

        $integrations = [
            ['name' => 'Email (SMTP)', 'desc' => 'Send emails from your CRM', 'status' => 'Connected', 'action' => 'Manage'],
            ['name' => 'Gmail', 'desc' => 'Sync emails and calendar', 'status' => null, 'action' => 'Connect'],
            ['name' => 'Slack', 'desc' => 'Get notified in Slack', 'status' => 'Connected', 'action' => 'Manage'],
            ['name' => 'Google Calendar', 'desc' => 'Sync meetings and events', 'status' => null, 'action' => 'Connect'],
            ['name' => 'Stripe', 'desc' => 'Payment processing', 'status' => null, 'action' => 'Connect'],
            ['name' => 'WhatsApp API', 'desc' => 'Send WhatsApp messages', 'status' => null, 'action' => 'Connect'],
        ];

        $settingsData = [
            'account' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roleLabel(),
            ],
            'api_key' => 'sk_prod_1234567890abcdefghijklmnopqrstuvwxyz',
            'notifications' => [
                ['label' => 'Email notifications for new leads', 'enabled' => true],
                ['label' => 'Daily activity digest', 'enabled' => true],
                ['label' => 'Deal pipeline updates', 'enabled' => false],
                ['label' => 'Task reminders', 'enabled' => false],
            ],
        ];

        $salesManagerPerformance = [
            ['name' => 'Emma Davis', 'deals' => 8, 'revenue' => '$125K', 'target' => '$100K', 'progress' => 100],
            ['name' => 'Michael Chen', 'deals' => 6, 'revenue' => '$95K', 'target' => '$100K', 'progress' => 95],
            ['name' => 'Sarah Johnson', 'deals' => 7, 'revenue' => '$115K', 'target' => '$100K', 'progress' => 100],
            ['name' => 'James Wilson', 'deals' => 5, 'revenue' => '$115K', 'target' => '$100K', 'progress' => 100],
        ];

        $salesExecActivity = [
            ['name' => 'John Smith', 'detail' => 'Converted to Customer', 'time' => '2 hours ago'],
            ['name' => 'Sarah Johnson', 'detail' => 'New Lead Added', 'time' => '4 hours ago'],
            ['name' => 'Mike Davis', 'detail' => 'Deal Won - $45K', 'time' => '1 day ago'],
        ];

        $allNavItems = [
            ['label' => 'Dashboard', 'route' => 'dashboard'],
            ['label' => 'Leads', 'route' => 'leads'],
            ['label' => 'Customers', 'route' => 'customers'],
            ['label' => 'Pipeline', 'route' => 'pipeline'],
            ['label' => 'Tasks', 'route' => 'tasks'],
            ['label' => 'Support', 'route' => 'support'],
            ['label' => 'Reports', 'route' => 'reports'],
            ['label' => 'Team', 'route' => 'team'],
        ];

        $allSaasNavItems = [
            ['label' => 'Billing', 'route' => 'billing'],
            ['label' => 'Integrations', 'route' => 'integrations'],
            ['label' => 'Settings', 'route' => 'settings'],
        ];

        $navItems = collect($allNavItems)
            ->filter(fn (array $item) => in_array($item['route'], $allowedSections, true))
            ->values()
            ->all();

        $saasNavItems = collect($allSaasNavItems)
            ->filter(fn (array $item) => in_array($item['route'], $allowedSections, true))
            ->values()
            ->all();

        $stages = [
            'prospect' => 'Prospect',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal',
            'negotiation' => 'Negotiation',
            'closed_won' => 'Closed Won',
        ];

        return view('dashboard', [
            'section' => $section,
            'navItems' => $navItems,
            'stats' => $stats,
            'alerts' => $alerts,
            'regions' => $regions,
            'monthlyRevenue' => $monthlyRevenue,
            'userGrowth' => $userGrowth,
            'companyGrowth' => $companyGrowth,
            'user' => $user,
            'customers' => $customers,
            'teamMembers' => $teamMembers,
            'leads' => $leads,
            'deals' => $deals,
            'tasks' => $tasks,
            'tickets' => $tickets,
            'stages' => $stages,
            'billingPlans' => $billingPlans,
            'billingHistory' => $billingHistory,
            'integrations' => $integrations,
            'settingsData' => $settingsData,
            'saasNavItems' => $saasNavItems,
            'salesManagerPerformance' => $salesManagerPerformance,
            'salesExecActivity' => $salesExecActivity,
        ]);
    }

    private function allowedSectionsFor(User $user): array
    {
        return match ($user->role) {
            'admin' => ['dashboard', 'leads', 'pipeline', 'tasks', 'reports', 'billing', 'integrations', 'settings'],
            'sales_manager' => ['dashboard', 'leads', 'pipeline', 'tasks', 'reports', 'billing', 'integrations', 'settings'],
            'sales_exec' => ['dashboard', 'leads', 'pipeline', 'tasks', 'billing', 'integrations', 'settings'],
            'support_agent' => ['dashboard', 'support', 'tasks', 'billing', 'integrations', 'settings'],
            default => ['dashboard', 'leads', 'customers', 'pipeline', 'tasks', 'support', 'reports', 'team', 'billing', 'integrations', 'settings'],
        };
    }
}
