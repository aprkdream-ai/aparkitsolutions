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
                            <a href="{{ $url }}" target="_blank" rel="noopener me" aria-label="{{ config('site.name') }} on {{ $network }}"><x-icon :name="$network" /></a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h2 class="footer-title">Services</h2>
                <ul class="footer-links">
                    @foreach ($footerServices->take(6) as $s)
                        <li><a href="{{ route('services.show', $s['slug']) }}">{{ $s['title'] }}</a></li>
                    @endforeach
                    <li><a href="{{ route('services.index') }}">All services →</a></li>
                </ul>
            </div>

            <div>
                <h2 class="footer-title">Company</h2>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    @foreach ($navItems->whereNotIn('route', ['services.index', 'about']) as $item)
                        <li><a href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
                    @endforeach
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <div>
                <h2 class="footer-title">Get in touch</h2>
                <ul class="footer-links footer-contact">
                    @if ($contact['email'])
                        <li><x-icon name="mail" /><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></li>
                    @endif
                    @if ($contact['phone'])
                        <li><x-icon name="phone" /><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a></li>
                    @endif
                    @if ($contact['address'])
                        <li><x-icon name="map-pin" /><span>{{ $contact['address'] }}</span></li>
                    @endif
                    <li><x-icon name="message" /><a href="{{ route('contact') }}">Send us your project details</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('site.legal_name') }}. All rights reserved.</p>
            <ul>
                <li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('legal.terms') }}">Terms &amp; Conditions</a></li>
                <li><a href="{{ route('legal.cookies') }}">Cookie Policy</a></li>
                <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
            </ul>
        </div>
    </div>
</footer>
