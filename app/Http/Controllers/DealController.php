<?php

namespace App\Http\Controllers;

use App\Http\Requests\Deals\DealRequest;
use App\Http\Requests\Deals\DealStageRequest;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\PipelineStage;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\AuditService;
use App\Services\CsvExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService,
        private readonly AuditService $auditService,
        private readonly CsvExportService $csvExportService,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Deal::class);

        $deals = Deal::query()
            ->with(['account', 'contact', 'owner', 'stage'])
            ->when($request->user()->role === 'sales_exec', fn ($query) => $query->where('owner_id', $request->user()->id))
            ->orderByDesc('value')
            ->paginate(15)
            ->withQueryString();

        if ($request->query('format') === 'csv') {
            return $this->csvExportService->download('deals.csv', ['Title', 'Account', 'Stage', 'Value', 'Probability'], $deals->getCollection()->map(fn (Deal $deal) => [
                $deal->title,
                $deal->account?->name,
                $deal->stage?->name,
                $deal->value,
                $deal->probability,
            ]));
        }

        return view('deals.index', [
            'deals' => $deals,
        ]);
    }

    public function pipeline(): View
    {
        $this->authorize('viewAny', Deal::class);

        $stages = PipelineStage::query()
            ->where('is_active', true)
            ->with(['deals' => function ($query): void {
                if (auth()->user()->role === 'sales_exec') {
                    $query->where('owner_id', auth()->id());
                }
            }, 'deals.account', 'deals.owner'])
            ->orderBy('order_column')
            ->get();

        return view('deals.pipeline', [
            'stages' => $stages,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Deal::class);

        return view('deals.create', $this->formData(new Deal()));
    }

    public function store(DealRequest $request): RedirectResponse
    {
        $this->authorize('create', Deal::class);

        $deal = Deal::create(array_merge($request->validated(), [
            'is_forecastable' => $request->boolean('is_forecastable'),
        ]));

        $this->activityService->record($deal, 'deal.created', 'Deal created', $request->user());

        return redirect()->route('deals.show', $deal)->with('status', 'Deal created successfully.');
    }

    public function show(Deal $deal): View
    {
        $this->authorize('view', $deal);

        $deal->load(['account', 'contact', 'owner', 'stage', 'activities.user']);

        return view('deals.show', [
            'deal' => $deal,
        ]);
    }

    public function edit(Deal $deal): View
    {
        $this->authorize('update', $deal);

        return view('deals.edit', $this->formData($deal));
    }

    public function update(DealRequest $request, Deal $deal): RedirectResponse
    {
        $this->authorize('update', $deal);

        $deal->update(array_merge($request->validated(), [
            'is_forecastable' => $request->boolean('is_forecastable'),
        ]));

        $this->activityService->record($deal, 'deal.updated', 'Deal updated', $request->user());

        return redirect()->route('deals.show', $deal)->with('status', 'Deal updated successfully.');
    }

    public function updateStage(DealStageRequest $request, Deal $deal): RedirectResponse
    {
        $this->authorize('update', $deal);

        $stage = PipelineStage::findOrFail($request->integer('stage_id'));

        $deal->update([
            'stage_id' => $stage->id,
            'probability' => $stage->probability,
        ]);

        $this->activityService->record($deal, 'deal.stage_changed', 'Deal moved to '.$stage->name, $request->user());
        $this->auditService->record('deal.stage_changed', $request->user(), $deal, $request->ip(), [
            'stage_id' => $stage->id,
            'stage_name' => $stage->name,
        ]);

        return back()->with('status', 'Deal stage updated successfully.');
    }

    private function formData(Deal $deal): array
    {
        return [
            'deal' => $deal,
            'accounts' => Account::query()->orderBy('name')->get(),
            'contacts' => Contact::query()->orderBy('name')->get(),
            'owners' => User::query()->whereIn('role', ['sales_manager', 'sales_exec'])->where('status', 'active')->orderBy('name')->get(),
            'stages' => PipelineStage::query()->where('is_active', true)->orderBy('order_column')->get(),
        ];
    }
}
