<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {
    }

    public function showLogin(): View|Response
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse|Response
    {
        $credentials = $request->validated();
        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user?->locked_until?->isFuture()) {
            return back()
                ->withInput($request->safe()->only('email', 'remember'))
                ->withErrors([
                    'email' => 'This account is locked until '.$user->locked_until->format('M j, Y g:i A').'.',
                ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($user) {
                $attempts = $user->failed_login_attempts + 1;
                $lock = $attempts >= 5 ? now()->addMinutes(15) : null;

                $user->forceFill([
                    'failed_login_attempts' => $attempts,
                    'locked_until' => $lock,
                ])->save();

                $this->auditService->record('auth.failed', $user, $user, $request->ip(), [
                    'failed_login_attempts' => $attempts,
                    'locked_until' => $lock?->toIso8601String(),
                ]);
            } else {
                $this->auditService->record('auth.failed', null, null, $request->ip(), [
                    'email' => $credentials['email'],
                ]);
            }

            return back()
                ->withInput($request->safe()->only('email', 'remember'))
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ]);
        }

        $request->session()->regenerate();
        $request->user()->forceFill([
            'last_active_at' => now(),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();
        $this->auditService->record('auth.signed_in', $request->user(), $request->user(), $request->ip());

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): Response
    {
        $user = $request->user();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $this->auditService->record('auth.signed_out', $user, $user, $request->ip());

        return redirect()->route('login');
    }
}
