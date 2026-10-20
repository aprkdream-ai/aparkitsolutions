<?php

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Content::industries();
        abort_if($industries->isEmpty(), 404);

        $seo = Seo::make(
            'Industries We Serve',
            'Software, web, and automation solutions tailored to the industries APARK IT Solutions works with.',
            ['breadcrumbs' => ['Industries' => null]]
        );
        if ($industries->every(fn ($i) => $i['draft'])) {
            $seo->noindex();
        }

        return view('industries.index', compact('seo', 'industries'));
    }

    public function show(string $slug)
    {
        $industry = Content::find('industries', $slug) ?? abort(404);

        $seo = Seo::make($industry['title'].' Software Solutions', $industry['summary'], [
            'breadcrumbs' => ['Industries' => route('industries.index'), $industry['title'] => null],
        ]);
        if ($industry['draft']) {
            $seo->noindex();
        }

        return view('industries.show', [
            'seo' => $seo,
            'industry' => $industry,
            'services' => collect($industry['solutions'])->map(fn ($s) => Content::find('services', $s))->filter(),
            'projects' => Content::projects()->where('industry', $slug),
        ]);
    }
}
