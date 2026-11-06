<?php

namespace App\Http\Requests;

use App\Support\Content;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/** Same protections as the contact form (captcha, consent, cleaning), with the project brief's fields. */
class ProjectBriefRequest extends ContactRequest
{
    protected array $cleaned = [
        'name', 'email', 'company', 'project_type', 'industry', 'challenge', 'solution',
        'integrations', 'budget', 'timeline', 'requirements',
    ];

    public function rules(): array
    {
        return Arr::only(parent::rules(), ['name', 'email', 'company', 'budget', 'consent', 'captcha']) + [
            'project_type' => ['required', 'string', Rule::in(array_keys(config('site.contact.project_types')))],
            'industry' => ['nullable', 'string', Rule::in(Content::industries()->keys()->push('other')->all())],
            'challenge' => ['required', 'string', 'min:20', 'max:3000'],
            'solution' => ['nullable', 'string', 'max:3000'],
            'integrations' => ['nullable', 'string', 'max:1000'],
            'timeline' => ['nullable', 'string', Rule::in(config('site.contact.timelines'))],
            'requirements' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'project_type.required' => 'Please choose the type of project.',
            'challenge.required' => 'Please describe the business challenge.',
            'challenge.min' => 'Please tell us a little more about the challenge (at least 20 characters).',
        ];
    }

    public function attributes(): array
    {
        return parent::attributes() + ['project_type' => 'project type', 'challenge' => 'business challenge'];
    }
}
