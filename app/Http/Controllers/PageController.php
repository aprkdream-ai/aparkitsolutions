<?php

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;

class PageController extends Controller
{
    public function home()
    {
        $seo = Seo::make(null, null, ['canonical' => route('home')])
            ->schema(Seo::organization())
            ->schema(Seo::localBusiness() ?? [])
            ->schema([
                '@type' => 'WebSite',
                '@id' => url('/').'#website',
                'name' => config('site.name'),
                'url' => url('/'),
                'publisher' => ['@id' => url('/').'#organization'],
            ]);

        return view('pages.home', [
            'seo' => $seo,
            'serviceGroups' => Content::serviceGroups(),
            'solutions' => Content::solutions(),
            'spotlight' => Content::projects()->where('spotlight', true)->take(3),
            'moreWork' => Content::projects()->where('featured', true)->where('spotlight', '!=', true)->take(4),
            'projectCount' => Content::projects()->count(),
            'industries' => Content::industries(),
            'tech' => Content::list('tech'),
            'faqs' => Content::list('faqs'),
            'testimonials' => Content::list('testimonials'),
            'posts' => Content::posts()->take(3),
        ]);
    }

    public function solutions()
    {
        return view('pages.solutions', [
            'seo' => Seo::make(
                'Business Problems We Solve With Custom Software',
                'Disconnected systems, manual processes, weak reporting, and legacy software: see how APARK IT Solutions approaches common operational problems.',
                ['breadcrumbs' => ['Solutions' => null]]
            ),
            'solutions' => Content::solutions(),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'seo' => Seo::make(
                'About Us — Software Development Team',
                'Learn about APARK IT Solutions: our mission, values, team, and the practical, transparent approach we bring to software, web, and automation projects.',
                ['breadcrumbs' => ['About' => null]]
            )->schema(Seo::organization()),
            'tech' => Content::list('tech'),
            'team' => Content::list('team'),
        ]);
    }

    public function legal(string $page)
    {
        $titles = [
            'privacy' => ['Privacy Policy', 'How APARK IT Solutions collects, uses, stores, and protects the personal information you share through this website and its contact form.'],
            'terms' => ['Terms and Conditions', 'The terms and conditions that govern your use of the APARK IT Solutions website, its content, and the information published on it.'],
            'cookies' => ['Cookie Policy', 'Which cookies the APARK IT Solutions website uses, including essential session cookies and Google Analytics, and how you can control them.'],
        ];
        [$title, $description] = $titles[$page];

        $seo = Seo::make($title, $description, ['breadcrumbs' => [$title => null]]);
        if (! config('site.legal_reviewed')) {
            $seo->noindex();
        }

        return view('pages.legal.'.$page, compact('seo', 'title'));
    }
}
