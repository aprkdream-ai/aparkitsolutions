@extends('layouts.app', ['seo' => \App\Support\Seo::make($title, $text)->noindex()])

@section('content')
<section class="section error-page">
    <div class="container container--narrow">
        <p class="error-code text-gradient">{{ $code }}</p>
        <h1>{{ $title }}</h1>
        <p class="lead">{{ $text }}</p>
        <div class="btn-row">
            <a class="btn btn--primary" href="{{ url('/') }}">Back to home</a>
            <a class="btn btn--outline" href="{{ url('/services') }}">Our services</a>
            <a class="btn btn--outline" href="{{ url('/contact') }}">Contact us</a>
        </div>
    </div>
</section>
@endsection
