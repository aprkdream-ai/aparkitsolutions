<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cache headers for public, session-less pages: browsers always revalidate,
 * shared caches (Hostinger CDN) may keep the HTML briefly. Purge the CDN after deploys.
 */
class PublicPageCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethodCacheable() && $response->getStatusCode() === 200 && ! $response->headers->getCookies()) {
            $response->headers->set('Cache-Control', 'public, max-age=0, s-maxage=600, stale-while-revalidate=60');
        }

        return $response;
    }
}
