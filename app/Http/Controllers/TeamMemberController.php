<?php

namespace App\Http\Controllers;

use App\Http\Requests\Team\TeamMemberStoreRequest;
use App\Http\Requests\Team\TeamMemberUpdateRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TeamMemberController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {
    }

    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        return view('team.index', [
            'members' => User::query()->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('team.create', [
            'member' => new User(),
        ]);
    }

    public function store(TeamMemberStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $member = User::create($request->validated());
        $this->auditService->record('team.member_created', $request->user(), $member, $request->ip(), [
            'role' => $member->role,
        ]);

        return redirect()->route('team.index')->with('status', 'Team member created successfully.');
    }

    public function edit(User $member): View
    {
        $this->authorize('update', $member);

        return view('team.edit', [
            'member' => $member,
        ]);
    }

    public function update(TeamMemberUpdateRequest $request, User $member): RedirectResponse
    {
        $this->authorize('update', $member);

        $payload = $request->validated();
        if (blank($payload['password'] ?? null)) {
            unset($payload['password']);
        }
        $member->update($payload);

        $this->auditService->record('team.member_updated', $request->user(), $member, $request->ip(), [
            'role' => $member->role,
        ]);

        return redirect()->route('team.index')->with('status', 'Team member updated successfully.');
    }
}
