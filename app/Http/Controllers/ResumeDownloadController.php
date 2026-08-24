<?php

namespace App\Http\Controllers;

use App\Contracts\PdfRenderer;
use App\Models\SiteSetting;
use App\Services\Resume\ResumeBuilder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeDownloadController extends Controller
{
    public function __construct(
        private ResumeBuilder $builder,
        private PdfRenderer $renderer,
    ) {}

    public function __invoke(): StreamedResponse
    {
        $locale = app()->getLocale();
        $template = SiteSetting::instance()->resume_template ?? 'default';

        $hash = $this->builder->contentHash($locale, $template);
        $relativePath = "resume/pdf/{$locale}/{$template}/{$hash}.pdf";

        if (! Storage::disk('local')->exists($relativePath)) {
            $absolutePath = Storage::disk('local')->path($relativePath);

            $dir = dirname($absolutePath);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $this->renderer->render(route('resume.print'), $absolutePath);
        }

        $filename = config('app.name').'-resume-'.$locale.'.pdf';

        return Storage::disk('local')->download($relativePath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
