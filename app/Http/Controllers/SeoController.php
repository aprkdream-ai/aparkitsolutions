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

    /** llms.txt (https://llmstxt.org): a Markdown overview of the site for AI assistants and AI search. */
    public function llms()
    {
        $c = config('site.contact');
        $line = fn (string $title, string $url, ?string $desc = null) => "- [{$title}]({$url})".($desc ? ": {$desc}" : '');

        $out = ['# '.config('site.name'), ''];
        $out[] = '> '.config('site.name').' is a custom software development company in India. We build websites, web and mobile applications, ERP/CRM systems, APIs and integrations, AI automation, and e-commerce platforms for businesses. '.config('site.slogans.0').'.';
        $out[] = '';
        $facts = array_filter([
            'Founded: 2024',
            $c['address'] ? 'Address: '.$c['address'] : null,
            $c['phone'] ? 'Phone / WhatsApp: '.$c['phone'] : null,
            'Contact form: '.route('contact'),
            'Languages: '.implode(', ', $c['languages'] ?: ['English']),
            'Projects delivered: '.Content::projects()->count().' across '.Content::industries()->count().' industries',
        ]);
        foreach ($facts as $f) {
            $out[] = '- '.$f;
        }

        $out[] = '';
        $out[] = '## Services';
        foreach (Content::services() as $s) {
            $out[] = $line($s['title'], route('services.show', $s['slug']), $s['summary']);
        }

        $out[] = '';
        $out[] = '## Portfolio (selected projects)';
        foreach (Content::projects()->reject(fn ($p) => $p['draft'])->sortByDesc('featured')->take(15) as $p) {
            $out[] = $line($p['title'], route('portfolio.show', $p['slug']), $p['summary']);
        }
        $out[] = $line('All projects', route('portfolio.index'));

        $out[] = '';
        $out[] = '## Industries';
        foreach (Content::industries()->reject(fn ($i) => $i['draft']) as $i) {
            $out[] = $line($i['title'], route('industries.show', $i['slug']), $i['summary']);
        }

        $out[] = '';
        $out[] = '## Technology';
        foreach (Content::list('tech') as $group => $items) {
            $out[] = "- {$group}: ".implode(', ', $items);
        }

        $out[] = '';
        $out[] = '## Insights';
        foreach (Content::posts()->reject(fn ($p) => $p['draft']) as $p) {
            $out[] = $line($p['title'], route('blog.show', $p['slug']), $p['description']);
        }

        $out[] = '';
        $out[] = '## Company';
        $out[] = $line('About us', route('about'), 'Mission, values, approach, and team');
        $out[] = $line('Contact', route('contact'), 'Start a project or ask a question');

        $out[] = '';
        $out[] = '## Optional';
        $out[] = $line('Sitemap', route('sitemap'));
        $out[] = $line('Privacy Policy', route('legal.privacy'));

        return response(implode("\n", $out)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /** Web app manifest (PWA / "Add to Home screen"). */
    public function manifest()
    {
        $icon = fn (string $file, string $size, string $purpose) => [
            'src' => asset_v('assets/img/brand/'.$file), 'sizes' => $size, 'type' => 'image/png', 'purpose' => $purpose,
        ];

        return response()->json([
            'id' => '/',
            'name' => config('site.name'),
            'short_name' => config('site.short_name'),
            'description' => config('site.seo.default_description'),
            'start_url' => '/',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait-primary',
            'background_color' => '#FFFFFF',
            'theme_color' => '#0F766E',
            'lang' => 'en',
            'categories' => ['business', 'productivity'],
            'icons' => [
                $icon('icon-192.png', '192x192', 'any'),
                $icon('icon-512.png', '512x512', 'any'),
                $icon('icon-maskable-512.png', '512x512', 'maskable'),
            ],
            'shortcuts' => [
                ['name' => 'Start a Project', 'url' => '/contact', 'icons' => [$icon('icon-192.png', '192x192', 'any')]],
                ['name' => 'Our Work', 'url' => '/portfolio', 'icons' => [$icon('icon-192.png', '192x192', 'any')]],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json; charset=UTF-8'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function robots()
    {
        $lines = config('site.indexable')
            ? ['User-agent: *', 'Disallow:', '', 'Sitemap: '.route('sitemap')]
            : ['# Non-production environment: indexing disabled.', 'User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
