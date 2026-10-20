<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactAcknowledgement;
use App\Mail\ContactSubmitted;
use App\Support\Captcha;
use App\Support\Content;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ContactController extends Controller
{
    /** Minimum seconds between rendering the form and submitting it (bots submit instantly). */
    public const MIN_FILL_SECONDS = 3;

    public function show(Request $request)
    {
        return view('pages.contact', [
            'seo' => Seo::make(
                'Contact Us — Start a Project',
                'Tell us about your software, website, app, or automation project. APARK IT Solutions will review your requirements and get back to you.',
                ['breadcrumbs' => ['Contact' => null]]
            )->schema(Seo::localBusiness() ?? []),
            'services' => Content::services(),
            'formToken' => Crypt::encryptString((string) now()->timestamp),
            'captcha' => Captcha::make(),
            'selectedService' => Content::services()->has($request->query('service')) ? $request->query('service') : null,
        ]);
    }

    /** Returns a fresh captcha question (used by the refresh button). */
    public function captcha()
    {
        $captcha = Captcha::make();

        return response()->json(['question' => $captcha['question'], 'token' => $captcha['token']])->header('Cache-Control', 'no-store');
    }

    public function store(ContactRequest $request)
    {

        if ($this->looksLikeSpam($request)) {
            Log::notice('Contact form spam blocked', ['ip' => $request->ip()]);

            // Respond as if successful so bots get no signal.
            return $this->respond($request, 'sent');
        }

        $data = $request->safe()->except(['consent', 'captcha']);
        $data['service_title'] = Content::find('services', $data['service'] ?? '')['title'] ?? (($data['service'] ?? null) === 'other' ? 'Other / not sure' : null);

        $this->storeBackup($data, $request);

        $recipient = config('site.contact.recipient');
        try {
            if (! $recipient) {
                throw new \RuntimeException('CONTACT_RECIPIENT is not configured.');
            }
            Mail::to($recipient)->send(new ContactSubmitted($data));
        } catch (Throwable $e) {
            Log::error('Contact form mail failed: '.$e->getMessage());

            // The enquiry is still stored when backups are enabled, so it is not lost.
            if (! config('site.contact.store_submissions')) {
                return $this->respond($request, 'error');
            }
        }

        // Confirmation to the visitor, sent after the response so the form stays fast.
        if (config('site.contact.autoreply')) {
            defer(function () use ($data) {
                try {
                    Mail::to($data['email'], $data['name'])->send(new ContactAcknowledgement($data));
                } catch (Throwable $e) {
                    Log::warning('Contact auto-reply failed: '.$e->getMessage());
                }
            });
        }

        return $this->respond($request, 'sent');
    }

    /** JSON for AJAX submissions (with a fresh captcha); redirect + flash for the no-JS fallback. */
    protected function respond(Request $request, string $status)
    {
        if ($request->expectsJson()) {
            $captcha = Captcha::make();
            $email = config('site.contact.email');

            return response()->json([
                'status' => $status,
                'title' => $status === 'sent' ? 'Thank you, your message has been sent.' : 'Sorry, we could not send your message.',
                'message' => $status === 'sent'
                    ? 'We have received your enquiry and will get back to you as soon as possible.'
                    : 'Please try again in a few minutes'.($email ? ' or email us at '.$email : '').'.',
                'captcha' => ['question' => $captcha['question'], 'token' => $captcha['token']],
            ], $status === 'sent' ? 200 : 503);
        }

        $redirect = $status === 'sent' ? redirect()->route('contact') : back()->withInput();

        return $redirect->with('contact_status', $status);
    }

    protected function looksLikeSpam(Request $request): bool
    {
        if (filled($request->input('website'))) { // honeypot
            return true;
        }

        try {
            $renderedAt = (int) Crypt::decryptString((string) $request->input('form_token'));
        } catch (Throwable) {
            return true;
        }

        $elapsed = now()->timestamp - $renderedAt;

        return $elapsed < self::MIN_FILL_SECONDS || $elapsed > 86400;
    }

    protected function storeBackup(array $data, Request $request): void
    {
        if (! config('site.contact.store_submissions')) {
            return;
        }

        try {
            Storage::disk('local')->append('contact/submissions.jsonl', json_encode($data + [
                'submitted_at' => now()->toIso8601String(),
                'ip' => $request->ip(),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } catch (Throwable $e) {
            Log::error('Contact form backup failed: '.$e->getMessage());
        }
    }
}
