<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // CSP in report-only mode — tighten to enforce after monitoring
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' challenges.cloudflare.com",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data:",
            "connect-src 'self'",
            'frame-src challenges.cloudflare.com',
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            'upgrade-insecure-requests',
        ]);

        $response->headers->set('Content-Security-Policy-Report-Only', $csp);

        return $response;
    }
}
