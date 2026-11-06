<?php

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;

class ServiceController extends Controller
{
    public function index()
    {
        return view('services.index', [
            'seo' => Seo::make(
                'Software & Web Development Services',
                'Custom software, ERP/CRM, SaaS, web and mobile apps, API integration, AI automation, e-commerce, and cloud consulting from APARK IT Solutions.',
                ['breadcrumbs' => ['Services' => null]]
            ),
            'services' => Content::services(),
            'groups' => Content::serviceGroups(),
        ]);
    }

    public function show(string $slug)
    {
        $service = Content::find('services', $slug) ?? abort(404);

        $seo = Seo::make($service['meta_title'], $service['meta_description'], [
            'breadcrumbs' => ['Services' => route('services.index'), $service['title'] => null],
        ])->schema([
            '@type' => 'Service',
            'name' => $service['title'],
            'description' => $service['meta_description'],
            'serviceType' => $service['title'],
            'url' => route('services.show', $slug),
            'provider' => Seo::organization(),
        ]);
        if ($service['draft']) {
            $seo->noindex();
        }

        return view('services.show', [
            'seo' => $seo,
            'service' => $service,
            'related' => collect($service['related'] ?? [])->map(fn ($s) => Content::find('services', $s))->filter(),
            'projects' => Content::projects()->filter(fn ($p) => in_array($slug, $p['services'] ?? []))->take(3),
            'posts' => Content::posts()->filter(fn ($p) => in_array($slug, $p['services']))->take(3),
            'solutions' => Content::solutions()->filter(fn ($s) => $s['services']->contains('slug', $slug))->take(3),
        ]);
    }
}
