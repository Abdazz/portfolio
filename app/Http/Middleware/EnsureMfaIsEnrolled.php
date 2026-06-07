<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defence-in-depth guard: ensures the authenticated admin has enrolled in TOTP
 * before reaching any panel page.
 *
 * Filament's `->multiFactorAuthentication([...], isRequired: true)` is the primary
 * enforcement mechanism and redirects unenrolled users to the MFA setup page.
 * This middleware is a safety net placed in `authMiddleware` that aborts with 403
 * if a request somehow bypasses Filament's own redirect.
 */
class EnsureMfaIsEnrolled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user instanceof HasAppAuthentication && $user->getAppAuthenticationSecret() === null) {
            abort(403, __('Multi-factor authentication must be configured before accessing the admin panel.'));
        }

        return $next($request);
    }
}
