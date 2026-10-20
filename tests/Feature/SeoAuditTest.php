<?php

namespace Tests\Feature;

use App\Support\Content;
use App\Support\Seo;
use Tests\TestCase;

/** Regression tests for the SEOptimer audit fixes (2026-10-20). */
class SeoAuditTest extends TestCase
{
    protected function allPublicUrls(): array
    {
        $urls = ['/', '/about', '/services', '/portfolio', '/industries', '/blog', '/contact', '/privacy-policy', '/terms-and-conditions', '/cookie-policy'];
        foreach (Content::services() as $s) { $urls[] = '/services/'.$s['slug']; }
        foreach (Content::industries() as $i) { $urls[] = '/industries/'.$i['slug']; }
        foreach (Content::projects() as $p) { $urls[] = '/portfolio/'.$p['slug']; }
        foreach (Content::posts() as $p) { $urls[] = '/blog/'.$p['slug']; }

        return $urls;
    }

    public function test_every_page_has_title_and_description_within_recommended_lengths(): void
    {
        foreach ($this->allPublicUrls() as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            preg_match('#<title>(.*?)</title>#s', $html, $t);
            preg_match('#<meta name="description" content="(.*?)">#s', $html, $d);
            $tl = mb_strlen(html_entity_decode($t[1]));
            $dl = mb_strlen(html_entity_decode($d[1]));
            $this->assertTrue($tl >= 30 && $tl <= Seo::TITLE_MAX, "{$url}: title length {$tl}");
            $this->assertTrue($dl >= Seo::DESC_MIN && $dl <= Seo::DESC_MAX, "{$url}: description length {$dl}");
        }
    }

    public function test_home_keywords_are_aligned_across_title_description_and_h1(): void
    {
        $html = $this->get('/')->getContent();
        preg_match('#<title>(.*?)</title>#s', $html, $t);
        preg_match('#<meta name="description" content="(.*?)">#s', $html, $d);
        preg_match('#<h1>(.*?)</h1>#s', $html, $h1);
        foreach ([$t[1], $d[1], strip_tags($h1[1])] as $text) {
            $this->assertStringContainsStringIgnoringCase('custom software development', $text);
        }
    }

    public function test_no_plain_text_email_addresses_in_html(): void
    {
        config(['site.contact.email' => 'info@aparkitsolutions.com']);
        foreach (['/', '/contact', '/privacy-policy', '/about'] as $url) {
            $html = $this->get($url)->getContent();
            $this->assertDoesNotMatchRegularExpression('/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[a-z]{2,}/', $html, $url);
        }
        $this->get('/contact')->assertSee('&#105;&#110;&#102;&#111;&#64;', false); // "info@" as character references
    }

    public function test_address_is_marked_up_as_postal_address(): void
    {
        config(['site.contact.address_parts' => ['street' => 'College Road, Bilaunji', 'locality' => 'Waidhan', 'region' => 'Madhya Pradesh', 'postal_code' => '486886', 'country' => 'IN', 'lat' => null, 'lng' => null], 'site.contact.address' => 'x']);
        $this->get('/')->assertSee('itemtype="https://schema.org/PostalAddress"', false)->assertSee('<span itemprop="postalCode">486886</span>', false);
    }

    public function test_llms_txt(): void
    {
        $res = $this->get('/llms.txt')->assertOk();
        $this->assertStringStartsWith('text/plain', $res->headers->get('Content-Type'));
        $body = $res->getContent();
        $this->assertStringStartsWith('# '.config('site.name'), $body);
        $this->assertStringContainsString('## Services', $body);
        $this->assertStringContainsString(route('services.show', 'custom-software-development'), $body);
        $this->assertStringNotContainsString('admin@gmail.com', $body);
    }

    public function test_public_pages_are_sessionless_and_cacheable_but_contact_keeps_its_session(): void
    {
        $home = $this->get('/');
        $this->assertEmpty($home->headers->getCookies(), 'Public pages must not set cookies');
        $this->assertStringContainsString('s-maxage', $home->headers->get('Cache-Control'));

        $contact = $this->get('/contact');
        $this->assertNotEmpty($contact->headers->getCookies(), 'Contact page needs the session cookie for CSRF');
        $this->assertStringNotContainsString('public', (string) $contact->headers->get('Cache-Control'));
    }

    public function test_css_is_inlined_with_absolute_font_urls(): void
    {
        config(['site.inline_css' => true]);
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('<style>', $html);
        $this->assertStringNotContainsString('app.min.css', $html);
        $this->assertStringContainsString(asset('assets/fonts').'/inter-var.woff2', $html);
        $this->assertStringNotContainsString('../fonts/', $html);
    }

    public function test_home_heading_count_is_proportionate(): void
    {
        $count = preg_match_all('/<h[1-6][\s>]/', $this->get('/')->getContent());
        $this->assertLessThanOrEqual(32, $count, "Home has {$count} headings; keep card labels as plain text");
        $this->assertSame(1, preg_match_all('/<h1[\s>]/', $this->get('/')->getContent()));
    }

    public function test_home_anchor_texts_are_unique_and_never_empty(): void
    {
        preg_match_all('#<a\s[^>]*href="[^"]+"[^>]*>(.*?)</a>#s', $this->get('/')->getContent(), $m);
        $texts = array_map(fn ($t) => mb_strtolower(trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($t))))), $m[1]);
        $this->assertNotContains('', $texts, 'Icon-only links need visually hidden text');
        $dupes = array_keys(array_filter(array_count_values($texts), fn ($n) => $n > 1));
        $this->assertSame([], $dupes, 'Duplicate anchor texts: '.implode(', ', $dupes));
    }

    public function test_pages_offer_social_sharing(): void
    {
        foreach (['/', '/services/custom-software-development', '/blog/website-vs-web-application'] as $url) {
            $html = $this->get($url)->getContent();
            foreach (['linkedin.com/sharing/share-offsite', 'twitter.com/intent/tweet', 'facebook.com/sharer', 'wa.me/?text='] as $needle) {
                $this->assertStringContainsString($needle, $html, "{$url} is missing {$needle}");
            }
            $this->assertSame(4, substr_count($html, 'Share this page on'), "{$url} should have exactly one share bar");
        }
    }
}
