@extends('layouts.app')

@push('head')
    <noscript><style>.brief-step + .brief-step { display: grid; } .brief-progress, [data-step-next], [data-step-back] { display: none; }</style></noscript>
@endpush

@section('content')
@php($contact = config('site.contact'))
@php($chosenType = old('project_type', $selectedType))
@php($chosenIndustry = old('industry', $selectedIndustry))
<x-page-hero :seo="$seo" eyebrow="Project brief" title="Discuss your project"
    lead="Three short steps. Tell us what the business needs, and we will review it and reply with questions or suggested next steps." />

<section class="section">
    <div class="container split split--wide-left split--top">
        <div class="panel">
            <div data-form-status aria-live="polite">
                @if (session('contact_status') === 'sent')
                    <div class="alert alert--success" role="status">
                        <x-icon name="check-circle" />
                        <div><strong>Thank you, your project brief has been received.</strong> We will review it and reply with questions or suggested next steps.</div>
                    </div>
                @elseif (session('contact_status') === 'error')
                    <div class="alert alert--error" role="alert">
                        <x-icon name="alert" />
                        <div><strong>Sorry, we could not send your brief.</strong> Please try again in a few minutes.</div>
                    </div>
                @elseif ($errors->any())
                    <div class="alert alert--error" role="alert">
                        <x-icon name="alert" />
                        <div><strong>Please check the highlighted fields.</strong> {{ $errors->count() === 1 ? 'One field needs' : $errors->count().' fields need' }} your attention.</div>
                    </div>
                @endif
            </div>

            <form class="form brief" method="POST" action="{{ route('project-brief.store') }}" novalidate data-loading data-ajax data-steps data-form-name="project_brief">
                @include('partials.form-guard')

                <ol class="brief-progress" aria-label="Progress">
                    <li data-step-label>Your project</li>
                    <li data-step-label>The challenge</li>
                    <li data-step-label>Your details</li>
                </ol>

                <fieldset class="brief-step" data-step>
                    <legend class="h3" tabindex="-1">Your project</legend>
                    <fieldset class="field">
                        <legend>Project type <span class="req" aria-hidden="true">*</span></legend>
                        <div class="options">
                            @foreach ($projectTypes as $value => $type)
                                <label class="option"><input type="radio" name="project_type" value="{{ $value }}" required data-msg="Please choose the type of project." @checked($chosenType === $value)><span>{{ $type['label'] }}</span></label>
                            @endforeach
                        </div>
                        @error('project_type')<span class="field-error" id="f-project_type-error">{{ $message }}</span>@enderror
                    </fieldset>
                    <x-field name="industry" label="Industry" type="select">
                        <option value="">Select an industry</option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry['slug'] }}" @selected($chosenIndustry === $industry['slug'])>{{ $industry['title'] }}</option>
                        @endforeach
                        <option value="other" @selected($chosenIndustry === 'other')>Other</option>
                    </x-field>
                    <div class="brief-nav"><button class="btn btn--primary" type="button" data-step-next>Continue <x-icon name="arrow-right" class="icon-arrow" /></button></div>
                </fieldset>

                <fieldset class="brief-step" data-step>
                    <legend class="h3" tabindex="-1">The challenge</legend>
                    <x-field name="challenge" label="Current business challenge" type="textarea" rows="4" required minlength="20" maxlength="3000"
                        data-msg="Please describe the challenge in at least 20 characters."
                        hint="What is not working today, and who does it affect?" />
                    <x-field name="solution" label="Required solution" type="textarea" rows="3" maxlength="3000"
                        hint="What do you think you need? It is fine if you are not sure yet." />
                    <x-field name="integrations" label="Expected integrations" maxlength="1000"
                        hint="For example accounting software, a payment gateway, or an existing CRM." />
                    <div class="brief-nav">
                        <button class="btn btn--outline" type="button" data-step-back>Back</button>
                        <button class="btn btn--primary" type="button" data-step-next>Continue <x-icon name="arrow-right" class="icon-arrow" /></button>
                    </div>
                </fieldset>

                <fieldset class="brief-step" data-step>
                    <legend class="h3" tabindex="-1">Your details</legend>
                    <div class="form-row">
                        <x-field name="name" label="Name" required autocomplete="name" maxlength="100" data-msg="Please enter your name." />
                        <x-field name="email" label="Business email" type="email" required autocomplete="email" maxlength="150" data-msg="Please enter a valid email address." />
                    </div>
                    <x-field name="company" label="Company name" autocomplete="organization" maxlength="150" />
                    <div class="form-row">
                        <x-field name="budget" label="Estimated budget" type="select">
                            <option value="">Select a range</option>
                            @foreach ($contact['budgets'] as $b)<option value="{{ $b }}" @selected(old('budget') === $b)>{{ $b }}</option>@endforeach
                        </x-field>
                        <x-field name="timeline" label="Project timeline" type="select">
                            <option value="">Select a timeline</option>
                            @foreach ($contact['timelines'] as $t)<option value="{{ $t }}" @selected(old('timeline') === $t)>{{ $t }}</option>@endforeach
                        </x-field>
                    </div>
                    <x-field name="requirements" label="Additional requirements" type="textarea" rows="3" maxlength="3000" />
                    @include('partials.form-verify')
                    <div class="brief-nav">
                        <button class="btn btn--outline" type="button" data-step-back>Back</button>
                        <button class="btn btn--primary" type="submit" data-loading-text="Sending…">
                            <span data-label>Send project brief</span> <x-icon name="arrow-right" class="icon-arrow" /><span class="spinner" aria-hidden="true"></span>
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>

        <div class="stack">
            <div class="aside-card aside-card--teal">
                <h2>What happens next?</h2>
                <ol class="check-list">
                    <li><x-icon name="check-circle" /><span>We review your brief.</span></li>
                    <li><x-icon name="check-circle" /><span>We reply to clarify details or arrange a call.</span></li>
                    <li><x-icon name="check-circle" /><span>You receive a recommended approach and estimate.</span></li>
                </ol>
            </div>
            <div class="aside-card">
                <h2>Prefer something quicker?</h2>
                <ul class="aside-links">
                    <li><a href="{{ route('contact') }}">Send a short message <x-icon name="arrow-right" /></a></li>
                    @if ($contact['phone'])<li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}">Call {{ $contact['phone'] }} <x-icon name="phone" /></a></li>@endif
                    @if ($wa = whatsapp_url('Hello APARK IT Solutions, I would like to discuss a project.'))<li><a href="{{ $wa }}" target="_blank" rel="noopener">Chat on WhatsApp <x-icon name="whatsapp" /></a></li>@endif
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
