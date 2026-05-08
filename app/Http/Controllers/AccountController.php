<?php

namespace App\Http\Controllers;

use App\Http\Requests\Accounts\AccountRequest;
use App\Models\Account;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\CsvExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService,
        private readonly CsvExportService $csvExportService,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Account::class);

        $accounts = Account::query()
            ->withCount(['contacts', 'deals', 'tickets'])
            ->with('owner')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $query->where('name', 'like', '%'.$request->string('search').'%');
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        if ($request->query('format') === 'csv') {
            return $this->csvExportService->download('accounts.csv', ['Name', 'Industry', 'Status', 'Owner', 'Contacts'], $accounts->getCollection()->map(fn (Account $account) => [
                $account->name,
                $account->industry,
                $account->status,
                $account->owner?->name,
                $account->contacts_count,
            ]));
        }

        return view('accounts.index', [
            'accounts' => $accounts,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Account::class);

        return view('accounts.create', [
            'account' => new Account(),
            'owners' => User::query()->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(AccountRequest $request): RedirectResponse
    {
        $this->authorize('create', Account::class);

        $account = Account::create($request->validated());
        $this->activityService->record($account, 'account.created', 'Account created', $request->user());

        return redirect()->route('accounts.show', $account)->with('status', 'Account created successfully.');
    }

    public function show(Account $account): View
    {
        $this->authorize('view', $account);

        $account->load(['owner', 'contacts.owner', 'deals.stage', 'tickets.assignee', 'activities.user']);

        return view('accounts.show', [
            'account' => $account,
        ]);
    }

    public function edit(Account $account): View
    {
        $this->authorize('update', $account);

        return view('accounts.edit', [
            'account' => $account,
            'owners' => User::query()->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function update(AccountRequest $request, Account $account): RedirectResponse
    {
        $this->authorize('update', $account);

        $account->update($request->validated());
        $this->activityService->record($account, 'account.updated', 'Account updated', $request->user());

        return redirect()->route('accounts.show', $account)->with('status', 'Account updated successfully.');
    }
}
