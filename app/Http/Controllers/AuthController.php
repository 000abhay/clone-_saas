<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|Response
    {
        if (Auth::check()) {
            return response('', 302, ['Location' => '/dashboard']);
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse|Response
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ]);
        }

        $request->session()->regenerate();
        $request->user()->forceFill(['last_active_at' => now()])->save();

        if ($request->session()->pull('url.intended')) {
            return response('', 302, ['Location' => '/dashboard']);
        }

        return response('', 302, ['Location' => '/dashboard']);
    }

    public function logout(Request $request): Response
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response('', 302, ['Location' => '/login']);
    }
}
