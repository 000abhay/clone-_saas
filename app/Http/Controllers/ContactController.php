<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contacts\ContactRequest;
use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\CsvExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService,
        private readonly CsvExportService $csvExportService,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Contact::class);

        $contacts = Contact::query()
            ->with(['account', 'owner'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $query->where('name', 'like', '%'.$request->string('search').'%');
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        if ($request->query('format') === 'csv') {
            return $this->csvExportService->download('contacts.csv', ['Name', 'Account', 'Lifecycle', 'Owner', 'Email'], $contacts->getCollection()->map(fn (Contact $contact) => [
                $contact->name,
                $contact->account?->name,
                $contact->lifecycle_stage,
                $contact->owner?->name,
                $contact->email,
            ]));
        }

        return view('contacts.index', [
            'contacts' => $contacts,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Contact::class);

        return view('contacts.create', [
            'contact' => new Contact(),
            'accounts' => Account::query()->orderBy('name')->get(),
            'owners' => User::query()->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $this->authorize('create', Contact::class);

        $contact = Contact::create(array_merge($request->validated(), [
            'tags' => $this->parseTags($request->input('tags')),
            'custom_fields' => $this->parseCustomFields($request->input('custom_fields')),
        ]));

        $this->activityService->record($contact, 'contact.created', 'Contact created', $request->user());

        return redirect()->route('contacts.show', $contact)->with('status', 'Contact created successfully.');
    }

    public function show(Contact $contact): View
    {
        $this->authorize('view', $contact);

        $contact->load(['account', 'owner', 'lead', 'deals.stage', 'tickets.assignee', 'activities.user']);

        return view('contacts.show', [
            'contact' => $contact,
        ]);
    }

    public function edit(Contact $contact): View
    {
        $this->authorize('update', $contact);

        return view('contacts.edit', [
            'contact' => $contact,
            'accounts' => Account::query()->orderBy('name')->get(),
            'owners' => User::query()->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function update(ContactRequest $request, Contact $contact): RedirectResponse
    {
        $this->authorize('update', $contact);

        $contact->update(array_merge($request->validated(), [
            'tags' => $this->parseTags($request->input('tags')),
            'custom_fields' => $this->parseCustomFields($request->input('custom_fields')),
        ]));

        $this->activityService->record($contact, 'contact.updated', 'Contact updated', $request->user());

        return redirect()->route('contacts.show', $contact)->with('status', 'Contact updated successfully.');
    }

    private function parseTags(?string $value): ?array
    {
        $tags = collect(explode(',', (string) $value))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        return $tags ?: null;
    }

    private function parseCustomFields(?string $value): ?array
    {
        $fields = collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(function (string $line): ?array {
                if (! str_contains($line, ':')) {
                    return null;
                }

                [$key, $fieldValue] = array_map('trim', explode(':', $line, 2));

                return $key !== '' ? [$key => $fieldValue] : null;
            })
            ->filter()
            ->reduce(fn (array $carry, array $item) => array_merge($carry, $item), []);

        return $fields ?: null;
    }
}
