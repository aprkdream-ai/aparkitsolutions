@php($contact = config('site.contact'))
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>We have received your enquiry</title></head>
<body style="margin:0;padding:24px 12px;background:#F6FBFA;font-family:Arial,Helvetica,sans-serif;color:#1F2937">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #E1ECEA;border-radius:14px;overflow:hidden">
    <tr>
        <td style="padding:22px 28px;background:#064E4A;background-image:linear-gradient(135deg,#0F766E,#064E4A)">
            <img src="{{ asset('assets/img/brand/apark-mark.png') }}" width="44" height="44" alt="" style="display:inline-block;vertical-align:middle;border-radius:10px">
            <span style="display:inline-block;vertical-align:middle;margin-left:10px;color:#ffffff;font-size:20px;font-weight:bold;letter-spacing:1px">APARK <span style="font-weight:normal;font-size:13px;letter-spacing:3px;color:#CDEDE8">IT SOLUTIONS</span></span>
        </td>
    </tr>
    <tr>
        <td style="padding:28px">
            <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#064E4A">Thank you, {{ $data['name'] }}!</h1>
            <p style="margin:0 0 16px;font-size:15px;line-height:1.6">We have received your enquiry and our team will review your requirements. We will get back to you as soon as possible, usually within one business day.</p>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;background:#F6FBFA;border:1px solid #E1ECEA;border-radius:10px">
                <tr><td style="padding:16px 18px;font-size:14px;line-height:1.7">
                    <strong style="display:block;color:#0F766E;font-size:12px;letter-spacing:1px;text-transform:uppercase;margin-bottom:6px">Your enquiry</strong>
                    @if (! empty($data['service_title']))Service: <strong>{{ $data['service_title'] }}</strong><br>@endif
                    @if (! empty($data['company']))Company: <strong>{{ $data['company'] }}</strong><br>@endif
                    @if (! empty($data['budget']))Budget: <strong>{{ $data['budget'] }}</strong><br>@endif
                    Submitted: <strong>{{ now()->timezone('Asia/Kolkata')->format('j M Y, g:i A') }} IST</strong>
                </td></tr>
            </table>

            <p style="margin:0 0 8px;font-size:15px;line-height:1.6"><strong>What happens next?</strong></p>
            <ol style="margin:0 0 20px;padding-left:20px;font-size:14px;line-height:1.7;color:#4B5563">
                <li>We review your requirements.</li>
                <li>We reply to clarify details or arrange a call.</li>
                <li>You receive a recommended approach and estimate.</li>
            </ol>

            <p style="margin:0 0 20px;font-size:14px;line-height:1.6;color:#4B5563">Need to add something? Simply reply to this email{!! $contact['phone'] ? ' or call us at <a href="tel:'.e(preg_replace('/[^0-9+]/', '', $contact['phone'])).'" style="color:#0F766E">'.e($contact['phone']).'</a>' : '' !!}.</p>

            <a href="{{ route('portfolio.index') }}" style="display:inline-block;padding:12px 20px;background:#0F766E;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:bold">See our recent work</a>
        </td>
    </tr>
    <tr>
        <td style="padding:18px 28px;background:#F6FBFA;border-top:1px solid #E1ECEA;font-size:12px;line-height:1.6;color:#6B7280">
            <strong style="color:#1F2937">{{ config('site.name') }}</strong> · {{ config('site.slogans.0') }}<br>
            @if ($contact['email'])<a href="mailto:{{ $contact['email'] }}" style="color:#0F766E">{{ $contact['email'] }}</a>@endif
            @if ($contact['phone']) · {{ $contact['phone'] }}@endif
            · <a href="{{ url('/') }}" style="color:#0F766E">{{ preg_replace('#^https?://#', '', url('/')) }}</a>
            @if ($contact['address'])<br>{{ $contact['address'] }}@endif
            <br><br>You are receiving this email because this address was used on the contact form at {{ preg_replace('#^https?://#', '', url('/')) }}. If this wasn't you, please ignore it.
        </td>
    </tr>
</table>
</body>
</html>
