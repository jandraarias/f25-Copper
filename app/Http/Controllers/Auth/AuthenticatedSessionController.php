<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate and start new session
        $request->authenticate();
        $request->session()->regenerate();

        // Get the authenticated user safely
        $user = Auth::user();

        // Get the correct redirect path based on role
        $redirectPath = RouteServiceProvider::redirectTo();

        // Get any previously intended URL
        $intendedUrl = session('url.intended');

        /**
         * If the stored intended URL doesn't match the user’s role,
         * forget it to prevent cross-role redirects
         */
        if ($intendedUrl && $user && ! str_contains($intendedUrl, $user->role)) {
            session()->forget('url.intended');
        }

        // Redirect to intended URL if valid, otherwise go to dashboard
        return redirect()->intended($redirectPath);
    }

    /**
     * Log out the user and destroy the session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Clear intended URL and session data
        $request->session()->forget('url.intended');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
