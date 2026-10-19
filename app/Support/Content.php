<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * File-based content repository. Entries live in content/*.php (arrays) and content/blog/*.md.
 * Entries flagged 'draft' => true are hidden unless config('site.show_drafts') is enabled.
 */
class Content
{
    protected static array $loaded = [];

    public static function path(string $file = ''): string
    {
        return base_path('content'.($file !== '' ? '/'.$file : ''));
    }

    /** Raw entries of a content/{name}.php file, keyed by slug, drafts filtered. */
    public static function collection(string $name): Collection
    {
        $items = static::$loaded[$name] ??= collect(require static::path($name.'.php'))
            ->map(fn (array $item, $slug) => $item + ['slug' => $slug, 'draft' => false]);

        return $items->filter(fn ($i) => static::visible($i));
    }

    /** Plain list/map files (faqs, tech, team, testimonials). */
    public static function list(string $name): array
    {
        return static::$loaded['list:'.$name] ??= require static::path($name.'.php');
    }

    public static function visible(array $item): bool
    {
        return empty($item['draft']) || config('site.show_drafts');
    }

    public static function services(): Collection
    {
        return static::collection('services')->filter(fn ($s) => $s['enabled'] ?? true);
    }

    public static function projects(): Collection
    {
        return static::collection('projects');
    }

    public static function industries(): Collection
    {
        return static::collection('industries');
    }

    public static function find(string $collection, string $slug): ?array
    {
        $items = $collection === 'services' ? static::services() : static::collection($collection);

        return $items->get($slug);
    }

    /** Blog posts, newest first. Parsed Markdown is cached and invalidated by file mtimes. */
    public static function posts(): Collection
    {
        $files = glob(static::path('blog/*.md')) ?: [];
        $signature = md5(implode('|', array_map(fn ($f) => $f.filemtime($f), $files)));

        $posts = static::$loaded['blog:'.$signature] ??= Cache::rememberForever(
            'content.blog.'.$signature,
            fn () => collect($files)->map(fn ($f) => static::parsePost($f))->filter()->values()->all()
        );

        return collect($posts)
            ->filter(fn ($p) => static::visible($p) && $p['date'] <= now()->toDateString())
            ->sortByDesc('date')
            ->values();
    }

    public static function post(string $slug): ?array
    {
        return static::posts()->firstWhere('slug', $slug);
    }

    public static function blogCategories(): Collection
    {
        return static::posts()->pluck('category')->unique()->mapWithKeys(fn ($c) => [Str::slug($c) => $c])->sort();
    }

    /** Parses "--- key: value ---" front matter followed by a Markdown body. */
    protected static function parsePost(string $file): ?array
    {
        $raw = file_get_contents($file);
        if (! preg_match('/\A---\R(.*?)\R---\R(.*)\z/s', $raw, $m)) {
            return null;
        }

        $meta = [];
        foreach (preg_split('/\R/', $m[1]) as $line) {
            if (preg_match('/^([a-z_]+):\s*(.*)$/', trim($line), $kv)) {
                $meta[$kv[1]] = trim($kv[2], " \"'");
            }
        }

        $body = $m[2];
        $words = str_word_count(strip_tags($body));

        return [
            'slug' => $meta['slug'] ?? Str::slug(preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', basename($file, '.md'))),
            'title' => $meta['title'] ?? 'Untitled',
            'description' => $meta['description'] ?? Str::limit(strip_tags(Str::markdown($body)), 155),
            'date' => $meta['date'] ?? date('Y-m-d', filemtime($file)),
            'updated' => $meta['updated'] ?? null,
            'author' => $meta['author'] ?? 'apark-team',
            'category' => $meta['category'] ?? 'Insights',
            'tags' => array_values(array_filter(array_map('trim', explode(',', $meta['tags'] ?? '')))),
            'image' => $meta['image'] ?? null,
            'image_alt' => $meta['image_alt'] ?? null,
            'draft' => filter_var($meta['draft'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'reading_time' => max(1, (int) round($words / 220)),
            'html' => Str::markdown($body, ['html_input' => 'strip', 'allow_unsafe_links' => false]),
        ];
    }
}
