<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\SupportTicket;
use App\Models\Task;
use App\Services\CsvExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct(
        private readonly CsvExportService $csvExportService,
    ) {
    }

    public function index(Request $request)
    {
        Gate::authorize('viewReports');

        $report = [
            ['metric' => 'Lead Conversion', 'value' => Lead::query()->count() ? round((Lead::query()->whereNotNull('converted_at')->count() / Lead::query()->count()) * 100, 1).'%' : '0%'],
            ['metric' => 'Forecast Revenue', 'value' => '$'.number_format((float) Deal::query()->where('status', 'open')->sum(DB::raw('value * probability / 100')), 0)],
            ['metric' => 'Overdue Tasks', 'value' => Task::query()->whereDate('due_date', '<', now()->toDateString())->whereNull('completed_at')->count()],
            ['metric' => 'Open Tickets', 'value' => SupportTicket::query()->whereIn('status', ['open', 'waiting', 'in_progress'])->count()],
        ];

        if ($request->query('format') === 'csv') {
            return $this->csvExportService->download('reports.csv', ['Metric', 'Value'], $report);
        }

        return view('reports.index', [
            'leadSources' => Lead::query()->select('source', DB::raw('count(*) as total'))->groupBy('source')->pluck('total', 'source'),
            'dealPipeline' => Deal::query()->select('pipeline_stages.name', 'pipeline_stages.order_column', DB::raw('count(deals.id) as total'), DB::raw('sum(deals.value) as value'))
                ->join('pipeline_stages', 'pipeline_stages.id', '=', 'deals.stage_id')
                ->groupBy('pipeline_stages.name', 'pipeline_stages.order_column')
                ->orderBy('pipeline_stages.order_column')
                ->get(),
            'taskStatuses' => Task::query()->select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status'),
            'ticketHealth' => SupportTicket::query()->select('priority', DB::raw('count(*) as total'))->groupBy('priority')->pluck('total', 'priority'),
            'recentActivity' => Activity::query()->with('user')->latest()->take(10)->get(),
            'summaryReport' => $report,
        ]);
    }
}
