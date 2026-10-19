<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_sitemap_is_valid_xml_and_excludes_drafts(): void
    {
        config(['site.show_drafts' => false]);
        $res = $this->get('/sitemap.xml')->assertOk();
        $this->assertStringStartsWith('application/xml', $res->headers->get('Content-Type'));

        $xml = simplexml_load_string($res->getContent());
        $this->assertNotFalse($xml);
        $locs = array_map('strval', iterator_to_array($xml->url->loc ?? [], false));
        $all = $res->getContent();

        $this->assertStringContainsString(route('services.show', 'custom-software-development'), $all);
        $this->assertStringContainsString(route('blog.show', 'website-vs-web-application'), $all);
        $this->assertStringNotContainsString('sample-', $all);
        $this->assertStringNotContainsString('/industries', $all);
        $this->assertStringNotContainsString('privacy-policy', $all, 'Legal pages stay out until reviewed');
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

    public function test_canonical_redirect_to_https_host(): void
    {
        config(['site.force_canonical' => true, 'app.url' => 'https://www.example.com']);
        $this->get('http://example.com/services?x=1')->assertStatus(301)->assertRedirect('https://www.example.com/services?x=1');
    }

    public function test_canonical_redirect_disabled_by_default(): void
    {
        config(['site.force_canonical' => false]);
        $this->get('/services')->assertOk();
    }
}
