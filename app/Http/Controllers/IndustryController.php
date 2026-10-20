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
            'Industries We Serve — Software Solutions',
            'Software, web, and automation solutions for healthcare, fintech, education, real estate, retail, travel, HR, SaaS, logistics, and manufacturing businesses.',
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

        $count = Content::projects()->where('industry', $slug)->count();
        $seo = Seo::make($industry['title'].' Software Solutions', $industry['summary'], [
            'fillers' => [
                "See {$count} ".\Illuminate\Support\Str::plural('project', $count).' we have delivered in this sector.',
                'Custom software, web and mobile apps by APARK IT Solutions.',
            ],
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
