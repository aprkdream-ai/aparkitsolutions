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

    public function test_drafts_are_hidden_unless_enabled(): void
    {
        config(['site.show_drafts' => false]);
        $this->get('/portfolio/sample-mobile-app')->assertNotFound();
        $this->get('/industries')->assertNotFound();
        $this->get('/')->assertDontSee('Draft · sample')->assertDontSee(route('portfolio.index').'"', false);
        $this->get('/portfolio')->assertOk()->assertSee('Case studies are on the way')->assertSee('noindex, follow', false);

        config(['site.show_drafts' => true]);
        $this->get('/portfolio/sample-mobile-app')->assertOk()->assertSee('noindex, follow', false);
        $this->get('/industries/healthcare')->assertOk()->assertSee('noindex, follow', false);
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
