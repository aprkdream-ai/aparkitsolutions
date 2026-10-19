New website enquiry

Name: {{ $data['name'] }}
Email: {{ $data['email'] }}
@if (! empty($data['phone']))Phone: {{ $data['phone'] }}
@endif
@if (! empty($data['company']))Company: {{ $data['company'] }}
@endif
@if (! empty($data['service_title']))Service: {{ $data['service_title'] }}
@endif
@if (! empty($data['budget']))Budget: {{ $data['budget'] }}
@endif

Message:
{{ $data['message'] }}
