Thank you, {{ $data['name'] }}!

We have received your enquiry and our team will review your requirements. We will get back to you as soon as possible, usually within one business day.

@if (! empty($data['service_title']))Service: {{ $data['service_title'] }}
@endif
@if (! empty($data['company']))Company: {{ $data['company'] }}
@endif
Submitted: {{ now()->timezone('Asia/Kolkata')->format('j M Y, g:i A') }} IST

What happens next?
1. We review your requirements.
2. We reply to clarify details or arrange a call.
3. You receive a recommended approach and estimate.

Need to add something? Simply reply to this email{{ config('site.contact.phone') ? ' or call us at '.config('site.contact.phone') : '' }}.

{{ config('site.name') }} | {{ url('/') }}
You are receiving this email because this address was used on the contact form at {{ preg_replace('#^https?://#', '', url('/')) }}. If this wasn't you, please ignore it.
