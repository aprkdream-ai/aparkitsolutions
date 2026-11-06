<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 301-redirects requests to the scheme and host configured in APP_URL
 * (http → https, www ↔ non-www) when FORCE_CANONICAL_URL=true. Also removes a front-controller
 * prefix such as /public, which would otherwise serve a self-canonical copy of the whole site.
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

        $basePath = rtrim($canonical['path'] ?? '', '/');
        $wrongBase = $request->getBaseUrl() !== $basePath;

        if ($wrongBase || $request->getScheme() !== $scheme || strcasecmp($request->getHost(), $host) !== 0) {
            $port = isset($canonical['port']) ? ':'.$canonical['port'] : '';
            $query = $request->getQueryString();
            $uri = $wrongBase ? $basePath.$request->getPathInfo().($query !== null ? '?'.$query : '') : $request->getRequestUri();

            return redirect()->to($scheme.'://'.$host.$port.$uri, 301);
        }

        return $next($request);
    }
}
