<?php

namespace Tests\Feature;

use App\Support\Content;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PagesTest extends TestCase
{
    public static function publicPages(): array
    {
        return [
            ['/'], ['/about'], ['/services'], ['/services/custom-software-development'], ['/blog'],
            ['/blog/website-vs-web-application'], ['/blog/category/web-development'], ['/contact'],
            ['/privacy-policy'], ['/terms-and-conditions'], ['/cookie-policy'], ['/portfolio'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_render_with_one_h1_and_seo_tags(string $uri): void
    {
        $html = $this->get($uri)->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), "Expected exactly one <h1> on {$uri}");
        $this->assertMatchesRegularExpression('/<title>[^<]{10,}<\/title>/', $html);
        $this->assertStringContainsString('<meta name="description"', $html);
        $this->assertStringContainsString('<link rel="canonical"', $html);
        $this->assertStringContainsString('property="og:title"', $html);
    }

    public function test_every_enabled_service_has_a_page_and_unique_title(): void
    {
        $titles = [];
        foreach (Content::services() as $slug => $service) {
            $html = $this->get("/services/{$slug}")->assertOk()->assertSee($service['title'])->getContent();
            preg_match('/<title>(.*?)<\/title>/', $html, $m);
            $titles[] = $m[1];
            $this->assertStringContainsString('"@type":"Service"', $html);
        }
        $this->assertSame(count($titles), count(array_unique($titles)), 'Service titles must be unique');
    }

    public function test_unknown_content_returns_custom_404(): void
    {
        $this->get('/services/does-not-exist')->assertNotFound()->assertSee('Page not found');
        $this->get('/blog/does-not-exist')->assertNotFound();
        $this->get('/random-page')->assertNotFound();
        $this->get('/blog?page=99')->assertNotFound();
    }

    public function test_portfolio_and_industries_render_real_content(): void
    {
        config(['site.show_drafts' => false]);
        $this->get('/portfolio')->assertOk()->assertSee('Bigul')->assertSee('index, follow', false)->assertSee('data-filter-bar', false);
        $this->get('/portfolio?category=e-commerce')->assertOk()->assertSee('noindex, follow', false);
        $this->get('/portfolio/bigul')->assertOk()->assertSee('https://bigul.co/', false)->assertSee('Key features');
        $this->get('/industries')->assertOk()->assertSee('Healthcare');
        $this->get('/industries/fintech')->assertOk()->assertSee('Projects we have delivered')->assertSee('Reco');
        $this->get('/')->assertSee('Selected projects')->assertSee('Qonsistic');
    }

    public function test_no_credentials_or_admin_urls_are_published(): void
    {
        foreach (['/portfolio', '/portfolio/qonsistic', '/portfolio/eventbus', '/portfolio/nexoracrm', '/sitemap.xml'] as $uri) {
            $html = $this->get($uri)->getContent();
            $this->assertStringNotContainsString('123456', $html, $uri);
            $this->assertStringNotContainsString('admin@gmail.com', $html, $uri);
            $this->assertDoesNotMatchRegularExpression('#https?://[^"\s]*/(admin|login|authentication)#', $html, $uri);
        }
    }

    public function test_draft_entries_are_hidden_unless_enabled(): void
    {
        $file = base_path('content/blog/2099-01-01-draft-test.md');
        file_put_contents($file, "---\ntitle: Draft Test Post\ndate: 2026-01-01\ndraft: true\n---\nBody text for the draft.");
        try {
            config(['site.show_drafts' => false]);
            $this->get('/blog/draft-test')->assertNotFound();
            config(['site.show_drafts' => true]);
            $this->get('/blog/draft-test')->assertOk()->assertSee('noindex, follow', false);
        } finally {
            unlink($file);
        }
    }

    public function test_blog_pagination_and_article_schema(): void
    {
        config(['site.blog.per_page' => 2]);
        $this->get('/blog')->assertOk()->assertSee('rel="next"', false);
        $this->get('/blog?page=2')->assertOk()->assertSee('<link rel="canonical" href="'.url('/blog?page=2').'"', false);

        $this->get('/blog/website-vs-web-application')
            ->assertOk()
            ->assertSee('"@type":"BlogPosting"', false)
            ->assertSee('<meta property="og:type" content="article">', false)
            ->assertSee('<table>', false);
    }

    public function test_breadcrumb_schema_on_inner_pages(): void
    {
        $this->get('/services/website-design-development')->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_main_menu_order_starts_with_home(): void
    {
        $html = $this->get('/about')->getContent();
        preg_match('#<nav class="nav" id="site-nav".*?</nav>#s', $html, $m);
        preg_match_all('#class="nav-link"[^>]*>([^<]+)</a>#', $m[0], $labels);
        $this->assertSame(['Home', 'About', 'Services', 'Portfolio', 'Industries', 'Insights', 'Contact'], array_map('trim', $labels[1]));
        $this->assertMatchesRegularExpression('#href="[^"]*/about"\s+aria-current="page"\s*>About#', $m[0]);
    }

    public function test_redirects(): void
    {
        $this->get('/contact-us')->assertStatus(301)->assertRedirect('/contact');
        $this->get('/about-us')->assertStatus(301);
    }

    public function test_security_headers(): void
    {
        $this->get('/')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeaderMissing('X-Powered-By');
        $this->assertStringContainsString("script-src 'self'", $this->get('/')->headers->get('Content-Security-Policy'));
    }
}
