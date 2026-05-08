<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\SupportTicket;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $scopeToUser = $user->role === 'sales_exec';
        $scopeTickets = $user->role === 'support_agent';

        $leadQuery = Lead::query();
        $dealQuery = Deal::query()->where('status', 'open');
        $taskQuery = Task::query();
        $ticketQuery = SupportTicket::query()->whereIn('status', ['open', 'waiting', 'in_progress']);

        if ($scopeToUser) {
            $leadQuery->where('assigned_user_id', $user->id);
            $dealQuery->where('owner_id', $user->id);
            $taskQuery->where('assigned_user_id', $user->id);
        }

        if ($scopeTickets) {
            $taskQuery->where('assigned_user_id', $user->id);
            $ticketQuery->where('assignee_id', $user->id);
        }

        $metrics = [
            'Active Leads' => (clone $leadQuery)->whereIn('status', ['new', 'working', 'qualified', 'nurturing'])->count(),
            'Unassigned Leads' => Lead::query()->whereNull('assigned_user_id')->count(),
            'Forecast Revenue' => '$'.number_format((float) (clone $dealQuery)->sum(DB::raw('value * probability / 100')), 0),
            'Overdue Tasks' => (clone $taskQuery)->whereDate('due_date', '<', now()->toDateString())->whereNull('completed_at')->count(),
            'Open Tickets' => (clone $ticketQuery)->count(),
            'SLA Breaches' => (clone $ticketQuery)->whereNotNull('breached_at')->count(),
        ];

        $leadFunnel = Lead::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $teamActivity = Activity::query()
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        $ticketSummary = SupportTicket::query()
            ->select('priority', DB::raw('count(*) as total'))
            ->whereIn('status', ['open', 'waiting', 'in_progress'])
            ->groupBy('priority')
            ->pluck('total', 'priority');

        return view('dashboard.index', [
            'metrics' => $metrics,
            'leadFunnel' => $leadFunnel,
            'teamActivity' => $teamActivity,
            'ticketSummary' => $ticketSummary,
            'myTasks' => (clone $taskQuery)->with('related')->orderBy('due_date')->take(6)->get(),
            'myDeals' => (clone $dealQuery)->with(['account', 'stage'])->orderByDesc('value')->take(6)->get(),
            'myTickets' => (clone $ticketQuery)->with('account')->orderBy('sla_resolution_due_at')->take(6)->get(),
        ]);
    }
}
