<?php

namespace App\Jobs;

use App\Contracts\PdfRenderer;
use App\Services\Resume\ResumeBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GenerateResumePdf implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public readonly string $locale,
        public readonly string $template,
    ) {}

    public function handle(PdfRenderer $renderer, ResumeBuilder $builder): void
    {
        // Swap locale so the print route renders in the correct language.
        $previousLocale = app()->getLocale();
        app()->setLocale($this->locale);

        try {
            $hash = $builder->contentHash($this->locale, $this->template);
            $relativePath = "resume/pdf/{$this->locale}/{$this->template}/{$hash}.pdf";

            // Skip if an up-to-date PDF already exists.
            if (Storage::disk('local')->exists($relativePath)) {
                return;
            }

            $absolutePath = Storage::disk('local')->path($relativePath);

            // Ensure the directory exists.
            $dir = dirname($absolutePath);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $printUrl = route('resume.print');

            $renderer->render($printUrl, $absolutePath);
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}
