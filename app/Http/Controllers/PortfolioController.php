<?php

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $projects = Content::projects();
        $categories = $projects->pluck('category')->unique()->mapWithKeys(fn ($c) => [Str::slug($c) => $c])->sort();
        $active = $categories->has($request->query('category')) ? $request->query('category') : null;

        $seo = Seo::make(
            'Portfolio & Case Studies',
            'Software projects by APARK IT Solutions: fintech and trading platforms, AI SaaS, CRM and ERP systems, e-commerce stores, booking apps, and business websites.',
            ['canonical' => route('portfolio.index'), 'breadcrumbs' => ['Portfolio' => null]]
        );
        // Filtered views and an empty portfolio are not indexed.
        if ($active || $projects->isEmpty() || $projects->every(fn ($p) => $p['draft'])) {
            $seo->noindex();
        }

        return view('portfolio.index', compact('seo', 'projects', 'categories', 'active') + ['industryCount' => $projects->pluck('industry')->filter()->unique()->count()]);
    }

    public function show(string $slug)
    {
        $project = Content::find('projects', $slug) ?? abort(404);

        $title = $project['title'].' — '.$project['category'].' Case Study';
        if (mb_strlen($title) > Seo::TITLE_MAX) {
            $title = $project['title'].' — Case Study';
        }
        $seo = Seo::make($title, $project['summary'], [
            'fillers' => [
                'Built by APARK IT Solutions with '.implode(', ', array_slice($project['tech'] ?? [], 0, 3)).'.',
                'View the full case study.',
            ],
            'image' => $project['image'] ?? null,
            'breadcrumbs' => ['Portfolio' => route('portfolio.index'), $project['title'] => null],
        ]);
        if ($project['draft']) {
            $seo->noindex();
        }

        return view('portfolio.show', [
            'seo' => $seo,
            'project' => $project,
            'services' => collect($project['services'] ?? [])->map(fn ($s) => Content::find('services', $s))->filter(),
            'industry' => $project['industry'] ? Content::find('industries', $project['industry']) : null,
            'more' => Content::projects()->except($slug)->where('category', $project['category'])->take(3),
        ]);
    }
}
