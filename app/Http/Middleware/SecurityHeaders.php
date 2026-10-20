<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $h = $response->headers;

        $h->set('X-Content-Type-Options', 'nosniff');
        $h->set('X-Frame-Options', 'SAMEORIGIN');
        $h->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $h->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');
        $ga = config('site.analytics.ga4') ? ' https://www.googletagmanager.com' : '';
        $gaConnect = $ga ? ' https://*.google-analytics.com https://*.analytics.google.com https://*.googletagmanager.com' : '';

        $h->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self'".$ga,
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: https:",
            "font-src 'self'",
            "connect-src 'self'".$gaConnect,
            'frame-src https://www.google.com https://maps.google.com',
            "form-action 'self'",
            "base-uri 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
        ]));

        if ($request->isSecure() && app()->isProduction()) {
            $h->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        if (! config('site.indexable')) {
            $h->set('X-Robots-Tag', 'noindex, nofollow');
        }

        $h->remove('X-Powered-By');

        return $response;
    }
}
