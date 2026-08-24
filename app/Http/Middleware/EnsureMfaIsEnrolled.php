<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defence-in-depth guard: ensures the authenticated admin has enrolled in TOTP
 * before reaching any panel page.
 *
 * Fortify's two-factor setup is the primary enforcement mechanism. This middleware
 * is a safety net placed in `authMiddleware` that aborts with 403 if a request
 * somehow bypasses that flow.
 */
class EnsureMfaIsEnrolled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user instanceof User && ! $user->hasEnabledTwoFactorAuthentication()) {
            abort(403, __('Multi-factor authentication must be configured before accessing the admin panel.'));
        }

        return $next($request);
    }
}
