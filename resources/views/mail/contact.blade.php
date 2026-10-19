<!DOCTYPE html>
<html lang="en">
<body style="margin:0;padding:24px;background:#F6FBFA;font-family:Arial,Helvetica,sans-serif;color:#1F2937">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #E1ECEA;border-radius:12px">
    <tr><td style="padding:20px 24px;background:#0F766E;color:#fff;border-radius:12px 12px 0 0;font-size:18px;font-weight:bold">New website enquiry</td></tr>
    <tr><td style="padding:24px">
        <table role="presentation" width="100%" cellpadding="6" cellspacing="0" style="font-size:14px">
            @foreach (['Name' => $data['name'], 'Email' => $data['email'], 'Phone' => $data['phone'] ?? null, 'Company' => $data['company'] ?? null, 'Service' => $data['service_title'] ?? null, 'Budget' => $data['budget'] ?? null] as $label => $value)
                @if ($value)
                    <tr><td style="width:120px;color:#6B7280;vertical-align:top">{{ $label }}</td><td style="font-weight:bold">{{ $value }}</td></tr>
                @endif
            @endforeach
        </table>
        <p style="margin:20px 0 6px;color:#6B7280;font-size:14px">Message</p>
        <div style="white-space:pre-wrap;font-size:15px;line-height:1.6;border-left:3px solid #0F766E;padding-left:12px">{{ $data['message'] }}</div>
        <p style="margin-top:24px;font-size:12px;color:#6B7280">Reply directly to this email to respond to {{ $data['name'] }}.</p>
    </td></tr>
</table>
</body>
</html>
