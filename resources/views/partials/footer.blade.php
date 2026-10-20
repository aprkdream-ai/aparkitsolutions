@php($contact = config('site.contact'))
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <x-brand footer />
                <p>We help businesses turn ideas into scalable digital solutions through custom software, web and mobile applications, automation, and technology consulting.</p>
                <p class="footer-slogan">{{ config('site.slogans.1') }}</p>
                @if (config('site.social'))
                    <div class="socials">
                        @foreach (config('site.social') as $network => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener me"><x-icon :name="$network" /><span class="visually-hidden">Follow {{ config('site.name') }} on {{ $network }}</span></a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="footer-title">Services</p>
                <ul class="footer-links">
                    @foreach ($footerServices->take(6) as $s)
                        <li><a href="{{ route('services.show', $s['slug']) }}">{{ $s['short'] ?? $s['title'] }}</a></li>
                    @endforeach
                    <li><a href="{{ route('services.index') }}">Browse all services →</a></li>
                </ul>
            </div>

            <div>
                <p class="footer-title">Company</p>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    @foreach ($navItems->whereNotIn('route', ['home', 'services.index', 'about', 'contact']) as $item)
                        <li><a href="{{ route($item['route']) }}">{{ $item['footer'] ?? $item['label'] }}</a></li>
                    @endforeach
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <p class="footer-title">Get in touch</p>
                <ul class="footer-links footer-contact">
                    @if ($contact['email'])
                        <li><x-icon name="mail" /><a href="mailto:{{ email_obfuscate($contact['email']) }}">{{ email_obfuscate($contact['email']) }}</a></li>
                    @endif
                    @if ($contact['phone'])
                        <li><x-icon name="phone" /><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a></li>
                    @endif
                    @if ($contact['address'])
                        <li itemscope itemtype="https://schema.org/Organization"><meta itemprop="name" content="{{ config('site.name') }}"><x-icon name="map-pin" />@include('partials.postal-address')</li>
                    @endif
                    <li><x-icon name="message" /><a href="{{ route('contact') }}">Send us your project details</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('site.legal_name') }}. All rights reserved.</p>
            @unless (request()->routeIs('blog.show'))
                @include('partials.share', ['class' => 'share--footer'])
            @endunless
            <ul>
                <li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('legal.terms') }}">Terms &amp; Conditions</a></li>
                <li><a href="{{ route('legal.cookies') }}">Cookie Policy</a></li>
                <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
            </ul>
        </div>
    </div>
</footer>
