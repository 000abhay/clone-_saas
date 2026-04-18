<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\SupportTicket;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    public function storeMember(Request $request): RedirectResponse
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

        return redirect()
            ->route('team')
            ->with('status', 'Team member added successfully.');
    }

    private function page(string $section): View
    {
        $user = auth()->user();

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

        $alerts = [
            ['title' => 'High API Usage', 'detail' => 'API usage at 85% of quota', 'tone' => 'amber'],
            ['title' => 'Server Maintenance', 'detail' => 'Scheduled for Sunday 2AM UTC', 'tone' => 'blue'],
            ['title' => 'Security Audit', 'detail' => 'Quarterly security audit passed', 'tone' => 'green'],
        ];

        $navItems = [
            ['label' => 'Dashboard', 'route' => 'dashboard'],
            ['label' => 'Leads', 'route' => 'leads'],
            ['label' => 'Customers', 'route' => 'customers'],
            ['label' => 'Pipeline', 'route' => 'pipeline'],
            ['label' => 'Tasks', 'route' => 'tasks'],
            ['label' => 'Support', 'route' => 'support'],
            ['label' => 'Reports', 'route' => 'reports'],
            ['label' => 'Team', 'route' => 'team'],
        ];

        $stages = [
            'prospect' => 'Prospect',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal',
            'negotiation' => 'Negotiation',
            'closed_won' => 'Closed Won',
        ];

        return view('crm.index', [
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
        ]);
    }
}
