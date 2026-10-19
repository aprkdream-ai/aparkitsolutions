<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 301-redirects requests to the scheme and host configured in APP_URL
 * (http → https, www ↔ non-www) when FORCE_CANONICAL_URL=true.
 */
class CanonicalUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('site.force_canonical') || ! in_array($request->method(), ['GET', 'HEAD'])) {
            return $next($request);
        }

        $canonical = parse_url(config('app.url'));
        $scheme = $canonical['scheme'] ?? 'https';
        $host = $canonical['host'] ?? $request->getHost();

        if ($request->getScheme() !== $scheme || strcasecmp($request->getHost(), $host) !== 0) {
            $port = isset($canonical['port']) ? ':'.$canonical['port'] : '';

            return redirect()->to($scheme.'://'.$host.$port.$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
