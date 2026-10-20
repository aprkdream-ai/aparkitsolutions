@extends('layouts.app')

@section('content')
@php($contact = config('site.contact'))
@php($chosen = old('service', $selectedService))
<x-page-hero :seo="$seo" eyebrow="Contact" title="Let’s talk about your project"
    lead="Share a few details about what you want to build or improve. We will review your message and respond with practical next steps.">
    @if ($contact['email'] || $contact['phone'] || whatsapp_url())
        <x-slot:aside>
            <div class="glass">
                <p class="glass-label">Prefer to reach us directly?</p>
                <ul class="hero-links">
                    @if ($contact['email'])<li><a href="mailto:{{ email_obfuscate($contact['email']) }}"><x-icon name="mail" /><span><small>Email</small>{{ email_obfuscate($contact['email']) }}</span></a></li>@endif
                    @if ($contact['phone'])<li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}"><x-icon name="phone" /><span><small>Call</small>{{ $contact['phone'] }}</span></a></li>@endif
                    @if ($wa = whatsapp_url('Hello APARK IT Solutions, I would like to discuss a project.'))<li><a href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /><span><small>WhatsApp</small>Chat with us</span></a></li>@endif
                </ul>
            </div>
        </x-slot:aside>
    @endif
</x-page-hero>

<section class="section">
    <div class="container split split--wide-left split--top">
        <div class="panel">
<div data-form-status aria-live="polite">
            @if (session('contact_status') === 'sent')
                <div class="alert alert--success" role="status">
                    <x-icon name="check-circle" />
                    <div><strong>Thank you, your message has been sent.</strong>We have received your enquiry and will get back to you as soon as possible.</div>
                </div>
            @elseif (session('contact_status') === 'error')
                <div class="alert alert--error" role="alert">
                    <x-icon name="alert" />
                    <div><strong>Sorry, we could not send your message.</strong>Please try again in a few minutes
                        @if ($contact['email']) or email us at <a href="mailto:{{ email_obfuscate($contact['email']) }}">{{ email_obfuscate($contact['email']) }}</a>@endif.</div>
                </div>
            @elseif ($errors->any())
                <div class="alert alert--error" role="alert">
                    <x-icon name="alert" />
                    <div><strong>Please check the highlighted fields.</strong>{{ $errors->count() === 1 ? 'One field needs' : $errors->count().' fields need' }} your attention.</div>
                </div>
            @endif
            </div>

            <h2 class="h3">Project enquiry</h2>
            <p class="muted small">Fields marked <span class="req">*</span> are required.</p>

            <form class="form" method="POST" action="{{ route('contact.store') }}" novalidate data-loading data-ajax>
                @csrf
                <input type="hidden" name="form_token" value="{{ $formToken }}">
                <input type="hidden" name="captcha_token" value="{{ $captcha['token'] }}" data-captcha-token>
                <div class="hp" aria-hidden="true">
                    <label for="f-website">Leave this field empty</label>
                    <input type="text" id="f-website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="form-row">
                    <x-field name="name" label="Full name" required autocomplete="name" maxlength="100" />
                    <x-field name="email" label="Email address" type="email" required autocomplete="email" maxlength="150" />
                </div>
                <div class="form-row">
                    <x-field name="phone" label="Phone" type="tel" autocomplete="tel" maxlength="30" />
                    <x-field name="company" label="Company" autocomplete="organization" maxlength="150" />
                </div>
                <div class="form-row">
                    <x-field name="service" label="Service of interest" type="select">
                        <option value="">Select a service</option>
                        @foreach ($services as $s)
                            <option value="{{ $s['slug'] }}" @selected($chosen === $s['slug'])>{{ $s['title'] }}</option>
                        @endforeach
                        <option value="other" @selected($chosen === 'other')>Other / not sure</option>
                    </x-field>
                    <x-field name="budget" label="Estimated budget" type="select">
                        <option value="">Select a range</option>
                        @foreach ($contact['budgets'] as $b)
                            <option value="{{ $b }}" @selected(old('budget') === $b)>{{ $b }}</option>
                        @endforeach
                    </x-field>
                </div>
                <x-field name="message" label="Project details" type="textarea" required maxlength="5000"
                    hint="What are you looking to build or improve? Include goals, timeline, and any existing systems."
                    placeholder="Tell us about your project…" />

                <div class="field captcha-field">
                    <label for="f-captcha">Security check: what is <strong data-captcha-question>{{ $captcha['question'] }}</strong>? <span class="req" aria-hidden="true">*</span></label>
                    <div class="captcha-row">
                        <span class="captcha-q" aria-hidden="true" data-captcha-question>{{ $captcha['question'] }} =</span>
                        <input class="input captcha-input" id="f-captcha" name="captcha" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="3" autocomplete="off" required
                            @error('captcha') aria-invalid="true" aria-describedby="f-captcha-error" @enderror>
                        <button class="captcha-refresh" type="button" data-captcha-refresh="{{ route('contact.captcha') }}" aria-label="Get a new security question"><x-icon name="refresh" /></button>
                    </div>
                    @error('captcha')<span class="field-error" id="f-captcha-error">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="checkbox">
                        <input type="checkbox" name="consent" value="1" required @checked(old('consent')) @error('consent') aria-invalid="true" aria-describedby="f-consent-error" @enderror>
                        <span>I agree that {{ config('site.name') }} may use these details to respond to my enquiry, as described in the <a href="{{ route('legal.privacy') }}">Privacy Policy</a>. <span class="req" aria-hidden="true">*</span></span>
                    </label>
                    @error('consent')<span class="field-error" id="f-consent-error">{{ $message }}</span>@enderror
                </div>

                <div>
                    <button class="btn btn--primary" type="submit" data-loading-text="Sending…">
                        <span data-label>Send message</span> <x-icon name="arrow-right" class="icon-arrow" /><span class="spinner" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>

        <div class="stack">
            <div class="aside-card aside-card--teal">
                <h2>What happens next?</h2>
                <ol class="check-list">
                    <li><x-icon name="check-circle" /><span>We review your requirements.</span></li>
                    <li><x-icon name="check-circle" /><span>We reply to clarify details or arrange a call.</span></li>
                    <li><x-icon name="check-circle" /><span>You receive a recommended approach and estimate.</span></li>
                </ol>
            </div>
            @if ($contact['email'] || $contact['phone'] || $contact['address'] || $contact['hours'] || whatsapp_url())
                <div class="aside-card">
                    <h2>Contact details</h2>
                    <ul class="contact-list">
                        @if ($contact['email'])
                            <li><span class="icon-tile"><x-icon name="mail" /></span><div><span class="label">Email</span><a href="mailto:{{ email_obfuscate($contact['email']) }}">{{ email_obfuscate($contact['email']) }}</a></div></li>
                        @endif
                        @if ($contact['phone'])
                            <li><span class="icon-tile"><x-icon name="phone" /></span><div><span class="label">Phone</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a></div></li>
                        @endif
                        @if ($wa = whatsapp_url())
                            <li><span class="icon-tile"><x-icon name="whatsapp" /></span><div><span class="label">WhatsApp</span><a href="{{ $wa }}" target="_blank" rel="noopener">Chat with us</a></div></li>
                        @endif
                        @if ($contact['address'])
                            <li><span class="icon-tile"><x-icon name="map-pin" /></span><div><span class="label">Address</span>@include('partials.postal-address')</div></li>
                        @endif
                        @if ($contact['hours'])
                            <li><span class="icon-tile"><x-icon name="clock" /></span><div><span class="label">Hours</span>{{ $contact['hours'] }}</div></li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </div>

    @if ($contact['address'] && $contact['map_embed'])
        <div class="container">
            <div class="map-card">
                <div class="map-card-info">
                    <span class="icon-tile icon-tile--solid"><x-icon name="map-pin" /></span>
                    <div>
                        <h2>Visit our office</h2>
                        @include('partials.postal-address')
                    </div>
                    <a class="btn btn--outline btn--sm" href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode(config('site.name').', '.$contact['address']) }}" target="_blank" rel="noopener">Get directions <x-icon name="external" /></a>
                </div>
                <iframe class="map-embed" src="{{ $contact['map_embed'] }}" title="Map showing the location of {{ config('site.name') }}" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
    @endif
</section>
@endsection
