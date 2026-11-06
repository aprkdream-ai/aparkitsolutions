New website enquiry

Name: {{ $data['name'] }}
Email: {{ $data['email'] }}
@if (! empty($data['phone']))Phone: {{ $data['phone'] }}
@endif
@if (! empty($data['company']))Company: {{ $data['company'] }}
@endif
@if (! empty($data['service_title']))Service: {{ $data['service_title'] }}
@endif
@if (! empty($data['industry_title']))Industry: {{ $data['industry_title'] }}
@endif
@if (! empty($data['budget']))Budget: {{ $data['budget'] }}
@endif
@if (! empty($data['timeline']))Timeline: {{ $data['timeline'] }}
@endif

Message:
{{ $data['message'] }}
