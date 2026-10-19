<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactSubmitted;
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
            ),
            'services' => Content::services(),
            'formToken' => Crypt::encryptString((string) now()->timestamp),
            'selectedService' => Content::services()->has($request->query('service')) ? $request->query('service') : null,
        ]);
    }

    public function store(ContactRequest $request)
    {
        if ($this->looksLikeSpam($request)) {
            Log::notice('Contact form spam blocked', ['ip' => $request->ip()]);

            // Respond as if successful so bots get no signal.
            return redirect()->route('contact')->with('contact_status', 'sent');
        }

        $data = $request->safe()->except('consent');
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
                return back()->withInput()->with('contact_status', 'error');
            }
        }

        return redirect()->route('contact')->with('contact_status', 'sent');
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
