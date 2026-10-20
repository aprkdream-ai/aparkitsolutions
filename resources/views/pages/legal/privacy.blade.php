@extends('pages.legal._layout')
@php($name = config('site.legal_name'))
@php($email = config('site.contact.email'))

@section('legal')
{{-- REVIEW REQUIRED: have this policy reviewed for the jurisdictions you operate in (e.g. GDPR, DPDP Act, CCPA). --}}
<p>This Privacy Policy explains how {{ $name }} (“we”, “us”) collects, uses, and protects personal information when you visit this website or contact us.</p>

<h2>Information we collect</h2>
<p>When you submit our contact form, we collect the details you provide: your name, email address, and message, plus optionally your phone number, company, service of interest, and budget range. We also record the date and time of submission and your IP address for security and spam prevention.</p>
<p>Our web server automatically keeps standard technical logs (such as IP address, browser type, and pages requested) to operate and secure the website.</p>

@if (config('site.analytics.ga4'))
<p>We use Google Analytics to collect aggregated information about how visitors use the website, such as pages viewed, approximate location (country/city), device, and browser. See our <a href="{{ route('legal.cookies') }}">Cookie Policy</a> for details and opt-out options.</p>
@endif

<h2>How we use your information</h2>
<ul>
    <li>To respond to your enquiry and discuss potential projects.</li>
    <li>To protect the website against spam, abuse, and security threats.</li>
    <li>To meet legal or regulatory obligations.</li>
</ul>
<p>We do not sell your personal information, and we do not use it for unrelated marketing without your consent.</p>

<h2>Legal basis</h2>
<p>Where applicable law requires a legal basis, we process enquiry data on the basis of your consent and our legitimate interest in responding to business enquiries.</p>

<h2>Sharing</h2>
<p>We share personal information only with service providers that help us operate the website, email, and analytics (such as hosting, email delivery, and Google Analytics), under appropriate confidentiality obligations, or where required by law.</p>

<h2>Retention</h2>
<p>We keep enquiry information only as long as needed to respond and manage any resulting business relationship, or as required by law, after which it is deleted.</p>

<h2>Security</h2>
<p>We use reasonable technical and organisational measures to protect personal information, including encrypted connections (HTTPS) and restricted access. No method of transmission over the internet is completely secure.</p>

<h2>Your rights</h2>
<p>Depending on your location, you may have the right to access, correct, delete, or restrict the use of your personal information, or to withdraw consent. To make a request, contact us using the details below.</p>

<h2>Cookies</h2>
<p>This website uses essential cookies{{ config('site.analytics.ga4') ? ' and Google Analytics cookies' : '' }}. See our <a href="{{ route('legal.cookies') }}">Cookie Policy</a> for details.</p>

<h2>Changes to this policy</h2>
<p>We may update this policy from time to time. The “last updated” date at the top shows when it was last revised.</p>

<h2>Contact</h2>
<p>For privacy questions or requests, please {!! $email ? 'email <a href="mailto:'.email_obfuscate($email).'">'.email_obfuscate($email).'</a> or ' : '' !!}use our <a href="{{ route('contact') }}">contact form</a>.</p>
@endsection
