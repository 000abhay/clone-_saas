<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasks\TaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\CsvExportService;
use App\Support\CrmOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService,
        private readonly CsvExportService $csvExportService,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);

        $tasks = Task::query()
            ->with(['assignedUser', 'related'])
            ->when(in_array($request->user()->role, ['sales_exec', 'support_agent'], true), function ($query) use ($request): void {
                $query->where('assigned_user_id', $request->user()->id);
            })
            ->orderByRaw('completed_at is null desc')
            ->orderBy('due_date')
            ->paginate(15)
            ->withQueryString();

        if ($request->query('format') === 'csv') {
            return $this->csvExportService->download('tasks.csv', ['Title', 'Status', 'Priority', 'Due Date', 'Assignee'], $tasks->getCollection()->map(fn (Task $task) => [
                $task->title,
                $task->status,
                $task->priority,
                optional($task->due_date)->toDateString(),
                $task->assignedUser?->name,
            ]));
        }

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Task::class);

        return view('tasks.create', $this->formData(new Task()));
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        $this->authorize('create', Task::class);

        $payload = $this->normalizedPayload($request);
        $task = Task::create($payload);

        if ($task->related) {
            $this->activityService->record($task->related, 'task.created', 'Task linked: '.$task->title, $request->user());
        }

        return redirect()->route('tasks.index')->with('status', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', $this->formData($task));
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $payload = $this->normalizedPayload($request);
        $payload['completed_at'] = $payload['status'] === 'completed' ? now() : null;

        $task->update($payload);

        if ($task->related) {
            $this->activityService->record($task->related, 'task.updated', 'Task updated: '.$task->title, $request->user());
        }

        return redirect()->route('tasks.index')->with('status', 'Task updated successfully.');
    }

    private function formData(Task $task): array
    {
        return [
            'task' => $task,
            'users' => User::query()->where('status', 'active')->orderBy('name')->get(),
            'relatedOptions' => $this->relatedOptions(),
        ];
    }

    private function normalizedPayload(TaskRequest $request): array
    {
        $payload = $request->validated();
        $map = CrmOptions::taskRelatedTypes();

        $payload['related_type'] = $request->filled('related_entity') ? $map[$request->input('related_entity')] : null;
        $payload['related_id'] = $request->filled('related_entity') ? $request->integer('related_id') : null;
        $payload['completed_at'] = $payload['status'] === 'completed' ? now() : null;
        unset($payload['related_entity']);

        return $payload;
    }

    private function relatedOptions(): array
    {
        return [
            'lead' => \App\Models\Lead::query()->orderBy('first_name')->get()->map(fn ($lead) => ['id' => $lead->id, 'label' => 'Lead: '.$lead->full_name]),
            'contact' => \App\Models\Contact::query()->orderBy('name')->get()->map(fn ($contact) => ['id' => $contact->id, 'label' => 'Contact: '.$contact->name]),
            'deal' => \App\Models\Deal::query()->orderBy('title')->get()->map(fn ($deal) => ['id' => $deal->id, 'label' => 'Deal: '.$deal->title]),
            'ticket' => \App\Models\SupportTicket::query()->orderBy('ticket_number')->get()->map(fn ($ticket) => ['id' => $ticket->id, 'label' => 'Ticket: '.$ticket->ticket_number.' '.$ticket->subject]),
        ];
    }
}
