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
            'Selected software, web, and mobile projects delivered by APARK IT Solutions, with the challenges, solutions, and technology behind each one.',
            ['canonical' => route('portfolio.index'), 'breadcrumbs' => ['Portfolio' => null]]
        );
        // Filtered views and an empty portfolio are not indexed.
        if ($active || $projects->isEmpty() || $projects->every(fn ($p) => $p['draft'])) {
            $seo->noindex();
        }

        return view('portfolio.index', compact('seo', 'projects', 'categories', 'active'));
    }

    public function show(string $slug)
    {
        $project = Content::find('projects', $slug) ?? abort(404);

        $seo = Seo::make($project['title'].' — Case Study', $project['summary'], [
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
            'more' => Content::projects()->except($slug)->take(2),
        ]);
    }
}
