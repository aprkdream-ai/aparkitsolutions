<?php

namespace App\Http\Requests;

use App\Support\Content;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $clean = fn ($v) => is_string($v) ? trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v)) : $v;

        $this->merge(collect($this->only(['name', 'email', 'phone', 'company', 'service', 'budget', 'message']))
            ->map($clean)
            ->all());
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', 'not_regex:/https?:\/\//i'],
            'email' => ['required', 'string', 'max:150', 'email:rfc,strict'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s().]{6,30}$/'],
            'company' => ['nullable', 'string', 'max:150'],
            'service' => ['nullable', 'string', Rule::in(Content::services()->keys()->push('other')->all())],
            'budget' => ['nullable', 'string', Rule::in(config('site.contact.budgets'))],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.not_regex' => 'Please enter your name without links.',
            'phone.regex' => 'Please enter a valid phone number (digits, spaces, +, - and brackets only).',
            'message.min' => 'Please tell us a little more about your project (at least 20 characters).',
            'consent.accepted' => 'Please confirm we may use these details to respond to your enquiry.',
        ];
    }

    public function attributes(): array
    {
        return ['email' => 'email address', 'consent' => 'consent'];
    }
}
