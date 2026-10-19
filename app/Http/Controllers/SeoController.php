<?php

namespace App\Http\Controllers;

use App\Support\Content;

class SeoController extends Controller
{
    public function sitemap()
    {
        $today = now()->toDateString();
        $static = fn (string $route, string $priority) => ['loc' => route($route), 'lastmod' => null, 'priority' => $priority];

        $urls = collect([
            $static('home', '1.0'),
            $static('services.index', '0.9'),
            $static('about', '0.7'),
            $static('contact', '0.8'),
            $static('blog.index', '0.7'),
        ]);

        $urls = $urls->merge(Content::services()->map(fn ($s) => ['loc' => route('services.show', $s['slug']), 'lastmod' => null, 'priority' => '0.8']));

        $projects = Content::projects()->reject(fn ($p) => $p['draft']);
        if ($projects->isNotEmpty()) {
            $urls->push($static('portfolio.index', '0.7'));
            $urls = $urls->merge($projects->map(fn ($p) => ['loc' => route('portfolio.show', $p['slug']), 'lastmod' => null, 'priority' => '0.6']));
        }

        $industries = Content::industries()->reject(fn ($i) => $i['draft']);
        if ($industries->isNotEmpty()) {
            $urls->push($static('industries.index', '0.6'));
            $urls = $urls->merge($industries->map(fn ($i) => ['loc' => route('industries.show', $i['slug']), 'lastmod' => null, 'priority' => '0.6']));
        }

        $urls = $urls->merge(Content::posts()->reject(fn ($p) => $p['draft'])->map(fn ($p) => [
            'loc' => route('blog.show', $p['slug']), 'lastmod' => $p['updated'] ?? $p['date'], 'priority' => '0.6',
        ]));

        if (config('site.legal_reviewed')) {
            foreach (['legal.privacy', 'legal.terms', 'legal.cookies'] as $r) {
                $urls->push($static($r, '0.2'));
            }
        }

        return response()->view('seo.sitemap', ['urls' => $urls->values(), 'today' => $today])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots()
    {
        $lines = config('site.indexable')
            ? ['User-agent: *', 'Disallow:', '', 'Sitemap: '.route('sitemap')]
            : ['# Non-production environment: indexing disabled.', 'User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
