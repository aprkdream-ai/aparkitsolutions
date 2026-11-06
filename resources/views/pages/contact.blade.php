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

            <h2 class="h3">Send us a message</h2>
            <p class="muted small">Fields marked <span class="req">*</span> are required.</p>

            <form class="form" method="POST" action="{{ route('contact.store') }}" novalidate data-loading data-ajax>
                @include('partials.form-guard')

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

                @include('partials.form-verify')

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
            <div class="aside-card">
                <h2>Planning a larger project?</h2>
                <p>Our three-step project brief helps you share the challenge, the solution you need, and any integrations.</p>
                <a class="link-arrow" href="{{ route('project-brief') }}">Fill in a project brief <x-icon name="arrow-right" /></a>
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
