<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Services\Resume\ResumeBuilder;
use App\Services\Resume\TemplateRegistry;
use Illuminate\View\View;

class ResumePrintController extends Controller
{
    public function __construct(
        private ResumeBuilder $builder,
        private TemplateRegistry $registry,
    ) {}

    public function __invoke(): View
    {
        $locale = app()->getLocale();
        $template = SiteSetting::instance()->resume_template ?? 'default';

        $data = $this->builder->build($locale, $template);

        return view('resume.print', [
            'data' => $data,
            'templateView' => $this->registry->view($template),
        ]);
    }
}
