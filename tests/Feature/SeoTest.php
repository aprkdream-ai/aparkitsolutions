<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_sitemap_is_valid_xml_and_excludes_drafts(): void
    {
        config(['site.show_drafts' => false, 'site.legal_reviewed' => false]);
        $res = $this->get('/sitemap.xml')->assertOk();
        $this->assertStringStartsWith('application/xml', $res->headers->get('Content-Type'));

        $xml = simplexml_load_string($res->getContent());
        $this->assertNotFalse($xml);
        $locs = array_map('strval', iterator_to_array($xml->url->loc ?? [], false));
        $all = $res->getContent();

        $this->assertStringContainsString(route('services.show', 'custom-software-development'), $all);
        $this->assertStringContainsString(route('blog.show', 'website-vs-web-application'), $all);
        $this->assertStringContainsString(route('portfolio.show', 'bigul'), $all);
        $this->assertStringContainsString(route('industries.show', 'healthcare'), $all);
        $this->assertStringNotContainsString('privacy-policy', $all, 'Legal pages stay out until reviewed');
    }

    public function test_reviewed_legal_pages_are_indexable_and_in_sitemap(): void
    {
        config(['site.legal_reviewed' => true, 'site.indexable' => true]);
        $this->get('/sitemap.xml')->assertSee(route('legal.privacy'), false);
        $this->get('/privacy-policy')->assertSee('index, follow', false)->assertDontSee('Template pending');
    }

    public function test_google_analytics_loads_only_when_indexable(): void
    {
        config(['site.analytics.ga4' => 'G-TEST123', 'site.indexable' => true]);
        $res = $this->get('/');
        $res->assertSee('data-ga4="G-TEST123"', false);
        $this->assertStringContainsString('https://www.googletagmanager.com', $res->headers->get('Content-Security-Policy'));
        $this->get('/cookie-policy')->assertSee('_ga');

        config(['site.indexable' => false]);
        $this->get('/')->assertDontSee('data-ga4', false);

        config(['site.analytics.ga4' => null]);
        $this->assertStringNotContainsString('googletagmanager', $this->get('/')->headers->get('Content-Security-Policy'));
    }

    public function test_organization_has_contact_point_and_social_profiles(): void
    {
        config(['site.social' => ['LinkedIn' => 'https://www.linkedin.com/company/aparkitsolutions']]);
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $this->get('/')->getContent(), $m);
        $org = collect(json_decode($m[1], true)['@graph'])->firstWhere('@type', 'Organization');
        $this->assertSame('ContactPoint', $org['contactPoint']['@type']);
        $this->assertSame('+919009434118', $org['contactPoint']['telephone']);
        $this->assertContains('https://www.linkedin.com/company/aparkitsolutions', $org['sameAs']);
    }

    public function test_robots_blocks_non_indexable_environments(): void
    {
        config(['site.indexable' => false]);
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');
        $this->get('/')->assertSee('noindex, follow', false)->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_robots_allows_indexing_in_production_and_lists_sitemap(): void
    {
        config(['site.indexable' => true]);
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: '.route('sitemap'))->assertDontSee('Disallow: /');
        $this->get('/')->assertSee('index, follow', false)->assertHeaderMissing('X-Robots-Tag');
    }

    public function test_home_has_organization_and_website_schema(): void
    {
        $html = $this->get('/')->getContent();
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m);
        $data = json_decode($m[1], true);
        $this->assertIsArray($data);
        $types = array_column($data['@graph'], '@type');
        $this->assertContains('Organization', $types);
        $this->assertContains('WebSite', $types);
    }

    public function test_local_business_schema_with_verified_address(): void
    {
        config(['site.contact.address_parts' => ['street' => 'College Road, Bilaunji', 'locality' => 'Waidhan', 'region' => 'Madhya Pradesh', 'postal_code' => '486886', 'country' => 'IN', 'lat' => '24.0751191', 'lng' => '82.6159398']]);
        foreach (['/', '/contact'] as $uri) {
            preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $this->get($uri)->getContent(), $m);
            $nodes = collect(json_decode($m[1], true)['@graph']);
            $lb = $nodes->firstWhere('@type', 'ProfessionalService');
            $this->assertNotNull($lb, $uri);
            $this->assertSame('486886', $lb['address']['postalCode']);
            $this->assertSame(24.0751191, $lb['geo']['latitude']);
        }
    }

    public function test_canonical_redirect_to_https_host(): void
    {
        config(['site.force_canonical' => true, 'app.url' => 'https://www.example.com']);
        $this->get('http://example.com/services?x=1')->assertStatus(301)->assertRedirect('https://www.example.com/services?x=1');
    }

    public function test_canonical_redirect_respects_trusted_proxy_https(): void
    {
        config(['site.force_canonical' => true, 'app.url' => 'https://aparkitsolutions.com']);
        \Illuminate\Http\Middleware\TrustProxies::at('*');
        $headers = ['X-Forwarded-Proto' => 'https', 'X-Forwarded-Port' => '443'];
        $this->get('http://aparkitsolutions.com/about', $headers)->assertOk();
        $this->get('http://www.aparkitsolutions.com/about', $headers)->assertRedirect('https://aparkitsolutions.com/about');
        \Illuminate\Http\Middleware\TrustProxies::flushState();
    }

    public function test_canonical_redirect_disabled_by_default(): void
    {
        config(['site.force_canonical' => false]);
        $this->get('/services')->assertOk();
    }
}
