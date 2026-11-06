<?php

namespace Tests\Feature;

use App\Mail\ContactAcknowledgement;
use App\Mail\ContactSubmitted;
use App\Support\Captcha;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectBriefTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('local');
        RateLimiter::clear('contact');
        config(['site.contact.recipient' => 'owner@example.test']);
    }

    protected function payload(array $overrides = []): array
    {
        $c = Captcha::make();

        return array_merge([
            'name' => 'Jane Client',
            'email' => 'jane@example.org',
            'company' => 'Acme Ltd',
            'project_type' => 'erp-crm',
            'industry' => 'manufacturing',
            'challenge' => 'Orders, stock, and invoices live in separate spreadsheets.',
            'solution' => 'One system for orders and stock.',
            'integrations' => 'Tally',
            'budget' => 'Medium project',
            'timeline' => 'Within 3–6 months',
            'requirements' => 'Hindi interface for the warehouse team.',
            'consent' => '1',
            'website' => '',
            'form_token' => Crypt::encryptString((string) (now()->timestamp - 30)),
            'captcha' => (string) $c['answer'],
            'captcha_token' => $c['token'],
        ], $overrides);
    }

    public function test_form_renders_with_protections_and_all_steps_in_the_html(): void
    {
        $html = $this->get('/project-brief')->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('name="website"', false)
            ->assertSee('name="captcha"', false)
            ->getContent();

        $this->assertSame(3, substr_count($html, 'data-step>'), 'All steps must be in the HTML so the form works without JavaScript');
    }

    public function test_service_and_industry_links_prefill_the_form(): void
    {
        $this->get('/project-brief?service=erp-crm-solutions&industry=healthcare')
            ->assertSee('value="erp-crm" required data-msg="Please choose the type of project." checked', false)
            ->assertSee('value="healthcare" selected', false);
        $this->get('/project-brief?service=unknown&industry=unknown')->assertOk()->assertDontSee(' checked', false);
    }

    public function test_malformed_prefill_parameters_are_ignored(): void
    {
        $this->get('/project-brief?service[]=x&industry[a][]=y')->assertOk();
        $this->get('/contact?service[a][]=x')->assertOk();
    }

    public function test_form_pages_are_never_publicly_cached(): void
    {
        foreach (['/project-brief', '/contact'] as $uri) {
            $header = (string) $this->get($uri)->headers->get('Cache-Control');
            $this->assertStringNotContainsString('s-maxage', $header, $uri);
            $this->assertStringNotContainsString('public', $header, $uri);
        }
    }

    public function test_valid_brief_is_emailed_and_stored_with_every_section(): void
    {
        $this->post('/project-brief', $this->payload())->assertRedirect('/project-brief')->assertSessionHas('contact_status', 'sent');

        Mail::assertSent(ContactSubmitted::class, function ($mail) {
            $d = $mail->data;

            return $mail->hasTo('owner@example.test')
                && $mail->envelope()->subject === 'New project brief from Jane Client — '.config('site.name')
                && $d['service_title'] === 'ERP / CRM' && $d['industry_title'] === 'Manufacturing' && $d['timeline'] === 'Within 3–6 months'
                && str_contains($d['message'], "Current business challenge:\nOrders") && str_contains($d['message'], "Expected integrations:\nTally");
        });
        Mail::assertSent(ContactAcknowledgement::class);
        $this->assertStringContainsString('"form":"project_brief"', Storage::disk('local')->get('contact/submissions.jsonl'));
    }

    public function test_ajax_submission_returns_json_with_a_fresh_captcha(): void
    {
        $this->postJson('/project-brief', $this->payload())->assertOk()
            ->assertJson(['status' => 'sent', 'title' => 'Thank you, your project brief has been received.'])
            ->assertJsonStructure(['captcha' => ['question', 'token']]);
    }

    public function test_required_fields_and_allowed_values_are_validated_on_the_server(): void
    {
        $this->postJson('/project-brief', $this->payload([
            'name' => '', 'email' => 'bad', 'project_type' => 'hacking', 'industry' => 'nope', 'challenge' => 'short', 'timeline' => 'yesterday', 'consent' => '',
        ]))->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'project_type', 'industry', 'challenge', 'timeline', 'consent']);
        Mail::assertNothingSent();
    }

    public function test_optional_fields_can_be_left_empty(): void
    {
        $this->postJson('/project-brief', $this->payload(['company' => '', 'industry' => '', 'solution' => '', 'integrations' => '', 'budget' => '', 'timeline' => '', 'requirements' => '']))->assertOk();
        Mail::assertSent(ContactSubmitted::class, fn ($m) => ! isset($m->data['company']) && ! str_contains($m->data['message'], 'Required solution'));
    }

    public function test_wrong_captcha_and_replayed_captcha_are_rejected(): void
    {
        $this->postJson('/project-brief', $this->payload(['captcha' => '999']))->assertStatus(422)->assertJsonValidationErrors('captcha');

        $payload = $this->payload();
        $this->postJson('/project-brief', $payload)->assertOk();
        $this->postJson('/project-brief', $payload)->assertStatus(422)->assertJsonValidationErrors('captcha');
    }

    public function test_spam_gets_a_success_response_but_sends_nothing(): void
    {
        $this->post('/project-brief', $this->payload(['website' => 'http://spam.test']))->assertRedirect('/project-brief');
        $this->post('/project-brief', $this->payload(['form_token' => Crypt::encryptString((string) now()->timestamp)]))->assertRedirect('/project-brief');
        Mail::assertNothingSent();
    }

    public function test_submissions_share_the_contact_rate_limit(): void
    {
        foreach (range(1, 5) as $i) {
            $this->post('/project-brief', $this->payload());
        }
        $this->post('/project-brief', $this->payload())->assertStatus(429);
    }

    public function test_visitor_confirmation_never_echoes_free_text(): void
    {
        $this->post('/project-brief', $this->payload(['challenge' => 'Visit http://spam.test for cheap watches today']));
        Mail::assertSent(ContactAcknowledgement::class, fn ($m) => ! str_contains($m->render(), 'cheap watches'));
    }
}
