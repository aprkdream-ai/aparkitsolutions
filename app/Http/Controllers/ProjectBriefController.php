<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectBriefRequest;
use App\Support\Captcha;
use App\Support\Content;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/** Guided project enquiry. Reuses the contact form's spam checks, backup, and email delivery. */
class ProjectBriefController extends ContactController
{
    protected string $returnRoute = 'project-brief';

    protected string $sentTitle = 'Thank you, your project brief has been received.';

    protected string $sentMessage = 'We will review it and reply with questions or suggested next steps.';

    public function show(Request $request)
    {
        $types = config('site.contact.project_types');
        $service = $request->query('service');
        $industry = $request->query('industry');

        return view('pages.project-brief', [
            'seo' => Seo::make(
                'Discuss Your Project — Project Brief',
                'Share your business challenge, required solution, and integrations in three short steps. APARK IT Solutions will review your brief and suggest next steps.',
                ['breadcrumbs' => ['Project Brief' => null]]
            ),
            'projectTypes' => $types,
            'industries' => Content::industries(),
            'formToken' => Crypt::encryptString((string) now()->timestamp),
            'captcha' => Captcha::make(),
            'selectedIndustry' => is_string($industry) && Content::industries()->has($industry) ? $industry : null,
            'selectedType' => $service ? collect($types)->search(fn ($t) => $t['service'] === $service) ?: null : null,
        ]);
    }

    public function submit(ProjectBriefRequest $request)
    {
        if ($this->looksLikeSpam($request)) {
            Log::notice('Project brief spam blocked', ['ip' => $request->ip()]);

            return $this->respond($request, 'sent'); // bots get no signal
        }

        $input = $request->safe();
        $sections = array_filter([
            'Current business challenge' => $input['challenge'],
            'Required solution' => $input['solution'] ?? null,
            'Expected integrations' => $input['integrations'] ?? null,
            'Additional requirements' => $input['requirements'] ?? null,
        ]);

        return $this->deliver($request, array_filter([
            'form' => 'project_brief',
            'name' => $input['name'],
            'email' => $input['email'],
            'company' => $input['company'] ?? null,
            'service_title' => config('site.contact.project_types')[$input['project_type']]['label'],
            'industry_title' => $this->industryTitle($input['industry'] ?? null),
            'budget' => $input['budget'] ?? null,
            'timeline' => $input['timeline'] ?? null,
            'message' => collect($sections)->map(fn ($text, $label) => "{$label}:\n{$text}")->implode("\n\n"),
        ]));
    }

    protected function industryTitle(?string $slug): ?string
    {
        return $slug === 'other' ? 'Other' : ($slug ? Content::find('industries', $slug)['title'] ?? null : null);
    }
}
