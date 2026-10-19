<?php

namespace Tests\Feature;

use App\Http\Controllers\ContactController;
use App\Mail\ContactSubmitted;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactFormTest extends TestCase
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
        return array_merge([
            'name' => 'Jane Client',
            'email' => 'jane@example.org',
            'phone' => '+1 (555) 010-0000',
            'company' => 'Acme Ltd',
            'service' => 'web-application-development',
            'budget' => 'Medium project',
            'message' => 'We need a customer portal for bookings and invoices.',
            'consent' => '1',
            'website' => '',
            'form_token' => Crypt::encryptString((string) (now()->timestamp - 30)),
        ], $overrides);
    }

    public function test_form_renders_with_csrf_and_honeypot(): void
    {
        $this->get('/contact')->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('name="website"', false)
            ->assertSee('name="form_token"', false);
    }

    public function test_service_preselected_from_query(): void
    {
        $this->get('/contact?service=mobile-app-development')->assertSee('value="mobile-app-development" selected', false);
    }

    public function test_valid_submission_sends_mail_and_stores_backup(): void
    {
        $this->post('/contact', $this->payload())
            ->assertRedirect('/contact')
            ->assertSessionHas('contact_status', 'sent');

        Mail::assertSent(ContactSubmitted::class, function (ContactSubmitted $mail) {
            return $mail->hasTo('owner@example.test')
                && $mail->hasReplyTo('jane@example.org')
                && $mail->data['service_title'] === 'Web Application Development';
        });
        Storage::disk('local')->assertExists('contact/submissions.jsonl');
    }

    public function test_validation_errors(): void
    {
        $this->post('/contact', $this->payload([
            'name' => '', 'email' => 'not-an-email', 'message' => 'short', 'consent' => null,
            'service' => 'hacking', 'phone' => 'abc<script>',
        ]))->assertSessionHasErrors(['name', 'email', 'message', 'consent', 'service', 'phone']);

        Mail::assertNothingSent();
    }

    public function test_output_is_escaped_after_validation_failure(): void
    {
        $this->from('/contact')->post('/contact', $this->payload(['company' => '<script>alert(1)</script>', 'email' => 'bad']));
        $this->get('/contact')->assertDontSee('<script>alert(1)</script>', false)->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_honeypot_is_silently_rejected(): void
    {
        $this->post('/contact', $this->payload(['website' => 'http://spam.example']))
            ->assertRedirect('/contact')->assertSessionHas('contact_status', 'sent');
        Mail::assertNothingSent();
    }

    public function test_too_fast_or_tampered_token_is_rejected(): void
    {
        $this->post('/contact', $this->payload(['form_token' => Crypt::encryptString((string) now()->timestamp)]));
        $this->post('/contact', $this->payload(['form_token' => 'tampered']));
        Mail::assertNothingSent();
        $this->assertGreaterThan(0, ContactController::MIN_FILL_SECONDS);
    }

    public function test_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $this->payload())->assertRedirect();
        }
        $this->post('/contact', $this->payload())->assertStatus(429)->assertSee('Too many requests');
    }

    public function test_mail_failure_still_succeeds_when_backup_enabled(): void
    {
        config(['site.contact.recipient' => null, 'site.contact.store_submissions' => true]);
        $this->post('/contact', $this->payload())->assertSessionHas('contact_status', 'sent');
        Storage::disk('local')->assertExists('contact/submissions.jsonl');

        config(['site.contact.store_submissions' => false]);
        $this->post('/contact', $this->payload())->assertSessionHas('contact_status', 'error');
    }

    public function test_csrf_is_enforced(): void
    {
        $this->app['env'] = 'production';
        $this->withMiddleware()->post('/contact', $this->payload())->assertStatus(419);
    }
}
