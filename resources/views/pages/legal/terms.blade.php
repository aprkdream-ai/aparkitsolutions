@extends('pages.legal._layout')
@php($name = config('site.legal_name'))

@section('legal')
{{-- REVIEW REQUIRED: confirm governing law, jurisdiction, and liability terms with a legal adviser. --}}
<p>These Terms and Conditions govern your use of this website, operated by {{ $name }}. By using the website, you agree to these terms.</p>

<h2>Use of the website</h2>
<p>You may use this website for lawful purposes only. You must not attempt to gain unauthorised access, interfere with its operation, or submit content that is unlawful, misleading, or harmful.</p>

<h2>Information on this website</h2>
<p>Content on this website is provided for general information. While we aim to keep it accurate and current, we make no warranties about its completeness or suitability for any particular purpose. Nothing on this website constitutes a binding offer. Project scope, pricing, and terms are agreed separately in writing.</p>

<h2>Intellectual property</h2>
<p>Unless otherwise stated, the content, design, and branding on this website belong to {{ $name }} and may not be reproduced without permission. Third-party trademarks belong to their respective owners.</p>

<h2>Links to other websites</h2>
<p>This website may link to third-party websites. We are not responsible for their content or practices.</p>

<h2>Limitation of liability</h2>
<p>To the extent permitted by law, {{ $name }} is not liable for any loss or damage arising from the use of, or inability to use, this website or its content.</p>

<h2>Changes</h2>
<p>We may update these terms at any time. Continued use of the website after changes means you accept the updated terms.</p>

<h2>Governing law</h2>
<p>These terms are governed by the laws of the jurisdiction in which {{ $name }} is registered, unless applicable law requires otherwise.</p>

<h2>Contact</h2>
<p>Questions about these terms can be sent through our <a href="{{ route('contact') }}">contact form</a>.</p>
@endsection
