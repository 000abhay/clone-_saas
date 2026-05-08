<?php

namespace App\Http\Controllers;

use App\Http\Requests\Leads\LeadConvertRequest;
use App\Http\Requests\Leads\LeadImportRequest;
use App\Http\Requests\Leads\LeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\CsvExportService;
use App\Services\LeadAssignmentService;
use App\Services\LeadConversionService;
use App\Services\LeadScoringService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService,
        private readonly CsvExportService $csvExportService,
        private readonly LeadAssignmentService $leadAssignmentService,
        private readonly LeadScoringService $leadScoringService,
        private readonly LeadConversionService $leadConversionService,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Lead::class);

        $leads = Lead::query()
            ->with(['assignedUser', 'contact'])
            ->when($request->user()->role === 'sales_exec', fn ($query) => $query->where('assigned_user_id', $request->user()->id))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $query->where(function ($query) use ($request): void {
                    $query->where('first_name', 'like', '%'.$request->string('search').'%')
                        ->orWhere('last_name', 'like', '%'.$request->string('search').'%')
                        ->orWhere('company_name', 'like', '%'.$request->string('search').'%');
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderByRaw('assigned_user_id is null desc')
            ->orderByDesc('score')
            ->paginate(15)
            ->withQueryString();

        if ($request->query('format') === 'csv') {
            return $this->csvExportService->download('leads.csv', ['Name', 'Company', 'Status', 'Score', 'Assignee'], $leads->getCollection()->map(fn (Lead $lead) => [
                $lead->full_name,
                $lead->company_name,
                $lead->status,
                $lead->score,
                $lead->assignedUser?->name,
            ]));
        }

        return view('leads.index', [
            'leads' => $leads,
            'unassignedCount' => Lead::query()->whereNull('assigned_user_id')->count(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Lead::class);

        return view('leads.create', [
            'lead' => new Lead(),
            'owners' => User::query()->whereIn('role', ['sales_manager', 'sales_exec'])->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(LeadRequest $request): RedirectResponse
    {
        $this->authorize('create', Lead::class);

        $payload = $request->validated();
        $payload['score'] = $this->leadScoringService->calculate($payload);

        $lead = Lead::create($payload);
        $this->leadAssignmentService->assignIfMissing($lead);
        $this->activityService->record($lead, 'lead.created', 'Lead captured', $request->user());

        return redirect()->route('leads.show', $lead)->with('status', 'Lead created successfully.');
    }

    public function show(Lead $lead): View
    {
        $this->authorize('view', $lead);

        $lead->load(['assignedUser', 'contact.account', 'activities.user', 'tasks.assignedUser']);

        return view('leads.show', [
            'lead' => $lead,
        ]);
    }

    public function edit(Lead $lead): View
    {
        $this->authorize('update', $lead);

        return view('leads.edit', [
            'lead' => $lead,
            'owners' => User::query()->whereIn('role', ['sales_manager', 'sales_exec'])->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function update(LeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $payload = $request->validated();
        $payload['score'] = $this->leadScoringService->calculate($payload);

        $lead->update($payload);
        $this->leadAssignmentService->assignIfMissing($lead);
        $this->activityService->record($lead, 'lead.updated', 'Lead updated', $request->user());

        return redirect()->route('leads.show', $lead)->with('status', 'Lead updated successfully.');
    }

    public function import(LeadImportRequest $request): RedirectResponse
    {
        $this->authorize('create', Lead::class);

        $handle = fopen($request->file('file')->getRealPath(), 'rb');
        $header = fgetcsv($handle);
        $created = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $duplicate = Lead::query()
                ->where(function ($query) use ($data): void {
                    if (! empty($data['email'])) {
                        $query->orWhere('email', $data['email']);
                    }

                    if (! empty($data['phone'])) {
                        $query->orWhere('phone', $data['phone']);
                    }
                })
                ->exists();

            if ($duplicate || empty($data['first_name'])) {
                $skipped++;

                continue;
            }

            $lead = Lead::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'company_name' => $data['company_name'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'source' => $data['source'] ?? 'import',
                'status' => $data['status'] ?? 'new',
                'notes' => $data['notes'] ?? null,
                'score' => $this->leadScoringService->calculate($data),
            ]);

            $this->leadAssignmentService->assignIfMissing($lead);
            $this->activityService->record($lead, 'lead.imported', 'Lead imported from CSV', $request->user());
            $created++;
        }

        fclose($handle);

        return redirect()->route('leads.index')->with('status', "Imported {$created} leads. Skipped {$skipped} duplicate/invalid rows.");
    }

    public function convert(LeadConvertRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('convert', $lead);

        $contact = $this->leadConversionService->convert($lead, $request->user(), [
            'account_name' => $request->input('account_name'),
            'contact_name' => $request->input('contact_name'),
            'industry' => $request->input('industry'),
            'job_title' => $request->input('job_title'),
            'lifecycle_stage' => $request->input('lifecycle_stage'),
            'tags' => $request->filled('tags') ? collect(explode(',', $request->string('tags')))->map(fn ($tag) => trim($tag))->filter()->values()->all() : null,
        ]);

        return redirect()->route('contacts.show', $contact)->with('status', 'Lead converted successfully.');
    }
}
