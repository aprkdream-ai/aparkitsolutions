@extends('pages.legal._layout')

@section('legal')
{{-- REVIEW REQUIRED: update this page if analytics, advertising, or embedded third-party content is added. --}}
<p>This Cookie Policy explains how this website uses cookies and similar technologies.</p>

<h2>What are cookies?</h2>
<p>Cookies are small text files stored on your device by your browser. They help websites function and remember information between page views.</p>

<h2>Cookies we use</h2>
<p>This website uses <strong>strictly necessary cookies</strong>, which are required for the site to work securely:</p>
<table>
    <thead><tr><th>Cookie</th><th>Purpose</th><th>Duration</th></tr></thead>
    <tbody>
        <tr><td><code>{{ config('session.cookie') }}</code></td><td>Maintains your session, for example to show form messages after you submit the contact form.</td><td>{{ config('session.lifetime') }} minutes</td></tr>
        <tr><td><code>XSRF-TOKEN</code></td><td>Protects forms against cross-site request forgery.</td><td>{{ config('session.lifetime') }} minutes</td></tr>
    </tbody>
</table>
@if (config('site.analytics.ga4'))
<h2>Analytics cookies</h2>
<p>We use <strong>Google Analytics 4</strong> to understand how visitors use the website (for example, which pages are visited and how people arrive at the site). This helps us improve our content. Google Analytics sets the following cookies:</p>
<table>
    <thead><tr><th>Cookie</th><th>Purpose</th><th>Duration</th></tr></thead>
    <tbody>
        <tr><td><code>_ga</code></td><td>Distinguishes unique visitors.</td><td>2 years</td></tr>
        <tr><td><code>_ga_*</code></td><td>Maintains session state for Google Analytics 4.</td><td>2 years</td></tr>
    </tbody>
</table>
<p>The information is aggregated and is not used to identify you personally. You can opt out with the <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener">Google Analytics opt-out browser add-on</a> or by blocking cookies in your browser. We do not use advertising cookies.</p>
@else
<p>We do not currently use analytics, advertising, or tracking cookies. If this changes, we will update this policy and ask for your consent where required.</p>
@endif

<h2>Third-party content</h2>
<p>If a page includes embedded content (such as a map), the provider may set its own cookies. That content is loaded only on the page where it appears.</p>

<h2>Managing cookies</h2>
<p>You can block or delete cookies in your browser settings. Blocking strictly necessary cookies may prevent forms from working.</p>

<h2>Contact</h2>
<p>If you have questions about this policy, please use our <a href="{{ route('contact') }}">contact form</a>.</p>
@endsection
