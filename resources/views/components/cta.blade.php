@props([
    'title' => 'Have an idea? Let’s turn it into real impact.',
    'text' => 'Tell us what you want to build or improve. We will review your requirements and suggest a practical way forward.',
    'service' => null,
])
<section class="section section--tight">
    <div class="container">
        <div class="cta-band">
            <p class="eyebrow">{{ config('site.slogans.1') }}</p>
            <h2>{{ $title }}</h2>
            <p>{{ $text }}</p>
            <div class="btn-row">
                <a class="btn btn--light" href="{{ route('contact', $service ? ['service' => $service] : []) }}">Discuss Your Project <x-icon name="arrow-right" class="icon-arrow" /></a>
                @if ($wa = whatsapp_url())
                    <a class="btn btn--ghost-light" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="whatsapp" /> Chat on WhatsApp</a>
                @elseif ($email = config('site.contact.email'))
                    <a class="btn btn--ghost-light" href="mailto:{{ email_obfuscate($email) }}"><x-icon name="mail" /> {{ email_obfuscate($email) }}</a>
                @else
                    <a class="btn btn--ghost-light" href="{{ route('services.index') }}">View All Services</a>
                @endif
            </div>
        </div>
    </div>
</section>
