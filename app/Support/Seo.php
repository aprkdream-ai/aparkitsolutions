<?php

namespace App\Support;

/**
 * Per-page SEO data consumed by resources/views/partials/seo.blade.php.
 */
class Seo
{
    public array $breadcrumbs = [];

    public array $schema = [];

    public function __construct(
        public string $title,
        public string $description,
        public ?string $canonical = null,
        public ?string $image = null,
        public string $type = 'website',
        public bool $index = true,
        public array $article = [],
    ) {
        $this->canonical ??= url()->current();
    }

    public static function make(?string $title = null, ?string $description = null, array $options = []): static
    {
        $seo = new static(
            title: $title ?? config('site.seo.default_title'),
            description: $description ?? config('site.seo.default_description'),
            canonical: $options['canonical'] ?? null,
            image: $options['image'] ?? null,
            type: $options['type'] ?? 'website',
            index: $options['index'] ?? true,
            article: $options['article'] ?? [],
        );

        if (isset($options['breadcrumbs'])) {
            $seo->breadcrumbs($options['breadcrumbs']);
        }

        return $seo;
    }

    /** @param array<string,string|null> $items label => url (null for current page) */
    public function breadcrumbs(array $items): static
    {
        $this->breadcrumbs = ['Home' => route('home')] + $items;

        $position = 0;
        $this->schema[] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($this->breadcrumbs)->map(fn ($url, $name) => [
                '@type' => 'ListItem',
                'position' => ++$position,
                'name' => $name,
                'item' => $url ?? $this->canonical,
            ])->values()->all(),
        ];

        return $this;
    }

    public function schema(array $node): static
    {
        $this->schema[] = $node;

        return $this;
    }

    public function noindex(): static
    {
        $this->index = false;

        return $this;
    }

    public function fullTitle(): string
    {
        $name = config('site.name');

        return str_contains($this->title, $name) ? $this->title : $this->title.config('site.seo.title_separator').$name;
    }

    public function imageUrl(): string
    {
        $image = $this->image ?? config('site.seo.default_image');

        return str_starts_with($image, 'http') ? $image : asset($image);
    }

    public function robots(): string
    {
        return $this->index && config('site.indexable')
            ? 'index, follow, max-image-preview:large'
            : 'noindex, follow';
    }

    /** Organization node shared across pages (only verified, configured facts). */
    public static function organization(): array
    {
        $contact = config('site.contact');

        return array_filter([
            '@type' => 'Organization',
            '@id' => url('/').'#organization',
            'name' => config('site.name'),
            'legalName' => config('site.legal_name'),
            'url' => url('/'),
            'logo' => asset('assets/img/brand/apark-mark.png'),
            'slogan' => config('site.tagline'),
            'email' => $contact['email'] ?: null,
            'telephone' => $contact['phone'] ?: null,
            'address' => $contact['address'] ?: null,
            'areaServed' => config('site.seo.area_served') ?: null,
            'sameAs' => array_values(config('site.social')) ?: null,
        ]);
    }

    public function jsonLd(): string
    {
        return json_encode(
            ['@context' => 'https://schema.org', '@graph' => $this->schema],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP
        );
    }
}
