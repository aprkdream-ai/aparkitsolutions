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
            description: static::fitDescription($description ?? config('site.seo.default_description'), $options['fillers'] ?? []),
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
        if ($node) {
            $this->schema[] = $node;
        }

        return $this;
    }

    public function noindex(): static
    {
        $this->index = false;

        return $this;
    }

    public const TITLE_MAX = 60;

    public const DESC_MIN = 120;

    public const DESC_MAX = 160;

    /** Appends the brand only when the result stays within 60 characters (search engines show the site name anyway). */
    public function fullTitle(): string
    {
        $name = config('site.name');
        if (str_contains($this->title, $name)) {
            return $this->title;
        }
        $branded = $this->title.config('site.seo.title_separator').$name;

        return mb_strlen($branded) <= self::TITLE_MAX ? $branded : $this->title;
    }

    /**
     * Keeps meta descriptions within 120–160 characters: short ones are extended with contextual
     * filler sentences (then a generic company sentence), long ones are trimmed at a word boundary.
     */
    public static function fitDescription(string $text, array $fillers = []): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $fillers[] = 'APARK IT Solutions builds custom software, websites, web and mobile apps for growing businesses.';
        $fillers[] = 'Talk to us about your project.';

        foreach ($fillers as $sentence) {
            if (mb_strlen($text) >= self::DESC_MIN) {
                break;
            }
            $candidate = rtrim($text, ' .').'. '.$sentence;
            if (mb_strlen($candidate) <= self::DESC_MAX) {
                $text = $candidate;
            }
        }

        if (mb_strlen($text) > self::DESC_MAX) {
            $cut = mb_substr($text, 0, self::DESC_MAX - 1);
            $text = rtrim(mb_substr($cut, 0, mb_strrpos($cut, ' ')), ' ,;:—-').'…';
        }

        return $text;
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
            'address' => static::postalAddress() ?: ($contact['address'] ?: null),
            'areaServed' => config('site.seo.area_served') ?: null,
            'sameAs' => array_values(config('site.social')) ?: null,
            'contactPoint' => $contact['phone'] ? array_filter([
                '@type' => 'ContactPoint',
                'telephone' => '+'.preg_replace('/\D+/', '', $contact['phone']),
                'email' => $contact['email'] ?: null,
                'contactType' => 'customer service',
                'areaServed' => 'IN',
                'availableLanguage' => $contact['languages'] ?: null,
            ]) : null,
        ]);
    }

    public static function postalAddress(): ?array
    {
        $a = config('site.contact.address_parts');
        if (empty($a['street']) || empty($a['locality'])) {
            return null;
        }

        return array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $a['street'],
            'addressLocality' => $a['locality'],
            'addressRegion' => $a['region'],
            'postalCode' => $a['postal_code'],
            'addressCountry' => $a['country'],
        ]);
    }

    /** LocalBusiness node, only when a verified address is configured. */
    public static function localBusiness(): ?array
    {
        $address = static::postalAddress();
        if (! $address) {
            return null;
        }
        $a = config('site.contact.address_parts');

        return array_filter([
            '@type' => 'ProfessionalService',
            '@id' => url('/').'#localbusiness',
            'name' => config('site.name'),
            'url' => url('/'),
            'image' => asset(config('site.seo.default_image')),
            'logo' => asset('assets/img/brand/apark-mark.png'),
            'telephone' => config('site.contact.phone') ?: null,
            'email' => config('site.contact.email') ?: null,
            'address' => $address,
            'geo' => ($a['lat'] && $a['lng']) ? ['@type' => 'GeoCoordinates', 'latitude' => (float) $a['lat'], 'longitude' => (float) $a['lng']] : null,
            'parentOrganization' => ['@id' => url('/').'#organization'],
        ]);
    }

    public function jsonLd(): string
    {
        $json = json_encode(
            ['@context' => 'https://schema.org', '@graph' => $this->schema],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP
        );

        // Hide email addresses from plain-text scrapers; "\u0040" is still "@" to any JSON parser.
        foreach (array_filter([config('site.contact.email')]) as $email) {
            $json = str_replace($email, str_replace('@', '\\u0040', $email), $json);
        }

        return $json;
    }
}
