<?php

namespace App\Providers;

use App\Support\Content;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($proxies = config('site.trusted_proxies')) {
            TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }

        // Contact form: 5 submissions per 10 minutes and 20 per day, per IP.
        RateLimiter::for('contact', fn (Request $request) => [
            Limit::perMinutes(10, 5)->by($request->ip()),
            Limit::perDay(20)->by('day:'.$request->ip()),
        ]);

        Paginator::defaultView('partials.pagination');

        // Shared layout data: navigation (hiding sections with no content) and footer services.
        View::composer(['partials.header', 'partials.footer'], function ($view) {
            $available = [
                'projects' => Content::projects()->isNotEmpty(),
                'industries' => Content::industries()->isNotEmpty(),
            ];

            $view->with([
                'navItems' => collect(config('site.nav'))->filter(fn ($i) => ! isset($i['requires']) || $available[$i['requires']]),
                'footerServices' => Content::services(),
            ]);
        });
    }
}
