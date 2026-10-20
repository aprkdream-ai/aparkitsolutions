<?php

namespace Tests\Feature;

use App\Http\Controllers\ContactController;
use App\Mail\ContactAcknowledgement;
use App\Mail\ContactSubmitted;
use App\Support\Captcha;
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
        ], $this->captcha(), $overrides);
    }

    /** A fresh, correctly answered captcha challenge. */
    protected function captcha(): array
    {
        $c = Captcha::make();

        return ['captcha' => (string) $c['answer'], 'captcha_token' => $c['token']];
    }

    protected function submit(array $overrides = [])
    {
        return $this->post('/contact', $this->payload($overrides));
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
        $this->submit()
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
        $this->submit([
            'name' => '', 'email' => 'not-an-email', 'message' => 'short', 'consent' => null,
            'service' => 'hacking', 'phone' => 'abc<script>',
        ])->assertSessionHasErrors(['name', 'email', 'message', 'consent', 'service', 'phone']);

        Mail::assertNothingSent();
    }

    public function test_output_is_escaped_after_validation_failure(): void
    {
        $this->from('/contact')->submit(['company' => '<script>alert(1)</script>', 'email' => 'bad']);
        $this->get('/contact')->assertDontSee('<script>alert(1)</script>', false)->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_honeypot_is_silently_rejected(): void
    {
        $this->submit(['website' => 'http://spam.example'])
            ->assertRedirect('/contact')->assertSessionHas('contact_status', 'sent');
        Mail::assertNothingSent();
    }

    public function test_too_fast_or_tampered_token_is_rejected(): void
    {
        $this->submit(['form_token' => Crypt::encryptString((string) now()->timestamp)]);
        $this->submit(['form_token' => 'tampered']);
        Mail::assertNothingSent();
        $this->assertGreaterThan(0, ContactController::MIN_FILL_SECONDS);
    }

    public function test_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->submit()->assertRedirect();
        }
        $this->submit()->assertStatus(429)->assertSee('Too many requests');
    }

    public function test_mail_failure_still_succeeds_when_backup_enabled(): void
    {
        config(['site.contact.recipient' => null, 'site.contact.store_submissions' => true]);
        $this->submit()->assertSessionHas('contact_status', 'sent');
        Storage::disk('local')->assertExists('contact/submissions.jsonl');

        config(['site.contact.store_submissions' => false]);
        $this->submit()->assertSessionHas('contact_status', 'error');
    }

    public function test_wrong_missing_or_tampered_captcha_is_rejected(): void
    {
        $this->submit(['captcha' => '99'])->assertSessionHasErrors('captcha');
        $this->submit(['captcha' => ''])->assertSessionHasErrors('captcha');
        $this->submit(['captcha_token' => 'tampered'])->assertSessionHasErrors('captcha');
        $this->submit(['captcha_token' => null])->assertSessionHasErrors('captcha');
        Mail::assertNothingSent();
    }

    public function test_captcha_token_is_single_use(): void
    {
        $c = $this->captcha();
        $this->submit($c)->assertSessionHas('contact_status', 'sent');
        $this->submit($c)->assertSessionHasErrors('captcha');
    }

    public function test_multiple_open_tabs_each_validate_independently(): void
    {
        $tabA = $this->captcha();
        $tabB = $this->captcha(); // a later page load must not invalidate tab A
        $this->submit($tabA)->assertSessionHas('contact_status', 'sent');
        $this->submit($tabB)->assertSessionHas('contact_status', 'sent');
    }

    public function test_expired_captcha_is_rejected(): void
    {
        $c = $this->captcha();
        $this->travel(Captcha::MAX_AGE + 60)->seconds();
        $this->submit($c + ['form_token' => \Illuminate\Support\Facades\Crypt::encryptString((string) (now()->timestamp - 30))])->assertSessionHasErrors('captcha');
    }

    public function test_answer_input_is_normalised(): void
    {
        $c = Captcha::make();
        $hindi = strtr((string) $c['answer'], array_combine(range(0, 9), ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९']));
        $this->submit(['captcha' => ' '.$hindi.' ', 'captcha_token' => $c['token']])->assertSessionHas('contact_status', 'sent');
    }

    public function test_contact_page_renders_captcha_and_refresh_returns_token(): void
    {
        $this->get('/contact')->assertOk()->assertSee('name="captcha"', false)->assertSee('name="captcha_token"', false);
        $json = $this->getJson('/contact/captcha')->assertOk()->json();
        $this->assertMatchesRegularExpression('/^\d+ [+−] \d+$/u', $json['question']);
        $this->assertNotEmpty($json['token']);
    }

    public function test_ajax_submission_returns_json_with_fresh_captcha(): void
    {
        $this->postJson('/contact', $this->payload())
            ->assertOk()
            ->assertJsonPath('status', 'sent')
            ->assertJsonStructure(['title', 'message', 'captcha' => ['question', 'token']]);
        Mail::assertSent(ContactSubmitted::class);
    }

    public function test_ajax_validation_errors_are_json_422(): void
    {
        $this->postJson('/contact', $this->payload(['email' => 'bad', 'captcha' => '999']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'captcha']);
        Mail::assertNothingSent();
    }

    public function test_ajax_mail_failure_without_backup_returns_503(): void
    {
        config(['site.contact.recipient' => null, 'site.contact.store_submissions' => false]);
        $this->postJson('/contact', $this->payload())->assertStatus(503)->assertJsonPath('status', 'error');
    }

    public function test_ajax_spam_gets_the_same_success_response(): void
    {
        $this->postJson('/contact', $this->payload(['website' => 'http://spam.example']))->assertOk()->assertJsonPath('status', 'sent');
        Mail::assertNothingSent();
    }

    public function test_backup_does_not_store_captcha_answer(): void
    {
        $this->submit();
        $line = Storage::disk('local')->get('contact/submissions.jsonl');
        $this->assertStringNotContainsString('"captcha"', $line);
    }

    public function test_visitor_receives_confirmation_email(): void
    {
        $this->withoutDefer();
        config(['site.contact.autoreply' => true]);

        $this->submit()->assertSessionHas('contact_status', 'sent');

        Mail::assertSent(ContactAcknowledgement::class, function (ContactAcknowledgement $mail) {
            $html = $mail->render();

            return $mail->hasTo('jane@example.org')
                && str_contains($html, 'Thank you, Jane Client!')
                && str_contains($html, 'Web Application Development')
                && ! str_contains($html, 'customer portal for bookings'); // free-text message is never echoed
        });
    }

    public function test_no_confirmation_email_for_spam_or_when_disabled(): void
    {
        $this->withoutDefer();
        $this->submit(['website' => 'http://spam.example']);
        Mail::assertNotSent(ContactAcknowledgement::class);

        config(['site.contact.autoreply' => false]);
        $this->submit();
        Mail::assertNotSent(ContactAcknowledgement::class);
        Mail::assertSent(ContactSubmitted::class);
    }

    public function test_csrf_is_enforced(): void
    {
        $this->app['env'] = 'production';
        $this->withMiddleware()->post('/contact', $this->payload())->assertStatus(419);
    }
}
