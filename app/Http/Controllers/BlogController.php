<?php

namespace App\Http\Controllers;

use App\Support\Content;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = $this->paginate(Content::posts(), $request);
        $page = $posts->currentPage();

        $seo = Seo::make(
            'Insights'.($page > 1 ? " — Page {$page}" : ''),
            'Practical articles on custom software, web development, integrations, and automation from the APARK IT Solutions team.'.($page > 1 ? " Page {$page}." : ''),
            ['canonical' => $page > 1 ? $posts->url($page) : route('blog.index'), 'breadcrumbs' => ['Insights' => null]]
        );

        return view('blog.index', [
            'seo' => $seo,
            'posts' => $posts,
            'categories' => Content::blogCategories(),
            'activeCategory' => null,
        ]);
    }

    public function category(Request $request, string $category)
    {
        $name = Content::blogCategories()->get($category) ?? abort(404);
        $posts = $this->paginate(Content::posts()->where('category', $name)->values(), $request);

        // Category archives are thin by nature: crawlable but not indexed.
        $seo = Seo::make($name.' Articles', "Articles about {$name} from the APARK IT Solutions team.", [
            'breadcrumbs' => ['Insights' => route('blog.index'), $name => null],
        ])->noindex();

        return view('blog.index', [
            'seo' => $seo,
            'posts' => $posts,
            'categories' => Content::blogCategories(),
            'activeCategory' => $category,
        ]);
    }

    public function show(string $slug)
    {
        $post = Content::post($slug) ?? abort(404);
        $author = config('site.blog.authors.'.$post['author']) ?? ['name' => $post['author'], 'bio' => null];

        $seo = Seo::make($post['title'], $post['description'], [
            'type' => 'article',
            'image' => $post['image'],
            'article' => ['published_time' => $post['date'], 'modified_time' => $post['updated'] ?? $post['date'], 'section' => $post['category'], 'tags' => $post['tags']],
            'breadcrumbs' => ['Insights' => route('blog.index'), $post['title'] => null],
        ])->schema([
            '@type' => 'BlogPosting',
            'headline' => $post['title'],
            'description' => $post['description'],
            'datePublished' => $post['date'],
            'dateModified' => $post['updated'] ?? $post['date'],
            'author' => ['@type' => 'Organization', 'name' => $author['name'], 'url' => url('/')],
            'publisher' => ['@id' => url('/').'#organization'],
            'mainEntityOfPage' => route('blog.show', $slug),
            'image' => $post['image'] ? asset($post['image']) : asset(config('site.seo.default_image')),
            'articleSection' => $post['category'],
            'keywords' => implode(', ', $post['tags']),
        ])->schema(Seo::organization());

        if ($post['draft']) {
            $seo->noindex();
        }

        $others = Content::posts()->where('slug', '!=', $slug);
        $related = $others->where('category', $post['category'])->merge($others->where('category', '!=', $post['category']))->take(3);

        return view('blog.show', compact('seo', 'post', 'author', 'related') + ['categorySlug' => Str::slug($post['category'])]);
    }

    protected function paginate(Collection $items, Request $request): LengthAwarePaginator
    {
        $perPage = max(1, config('site.blog.per_page'));
        $page = max(1, (int) $request->query('page', 1));
        $last = max(1, (int) ceil($items->count() / $perPage));
        abort_if($page > $last, 404);

        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, [
            'path' => $request->url(),
        ]);
    }
}
