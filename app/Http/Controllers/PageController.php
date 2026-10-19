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
            ->schema([
                '@type' => 'WebSite',
                '@id' => url('/').'#website',
                'name' => config('site.name'),
                'url' => url('/'),
                'publisher' => ['@id' => url('/').'#organization'],
            ]);

        return view('pages.home', [
            'seo' => $seo,
            'services' => Content::services(),
            'projects' => Content::projects()->take(3),
            'industries' => Content::industries(),
            'tech' => Content::list('tech'),
            'faqs' => Content::list('faqs'),
            'testimonials' => Content::list('testimonials'),
            'posts' => Content::posts()->take(3),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'seo' => Seo::make(
                'About Us',
                'Learn about APARK IT Solutions: our mission, values, and the practical, transparent approach we bring to software, web, and automation projects.',
                ['breadcrumbs' => ['About' => null]]
            )->schema(Seo::organization()),
            'tech' => Content::list('tech'),
            'team' => Content::list('team'),
        ]);
    }

    public function legal(string $page)
    {
        $titles = [
            'privacy' => ['Privacy Policy', 'How APARK IT Solutions collects, uses, and protects personal information submitted through this website.'],
            'terms' => ['Terms and Conditions', 'The terms that govern use of the APARK IT Solutions website.'],
            'cookies' => ['Cookie Policy', 'Information about the cookies used on the APARK IT Solutions website.'],
        ];
        [$title, $description] = $titles[$page];

        $seo = Seo::make($title, $description, ['breadcrumbs' => [$title => null]]);
        if (! config('site.legal_reviewed')) {
            $seo->noindex();
        }

        return view('pages.legal.'.$page, compact('seo', 'title'));
    }
}
