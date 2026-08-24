<?php

namespace App\Filament\Widgets;

use App\Jobs\GenerateResumePdf;
use App\Services\Resume\TemplateRegistry;
use Filament\Actions\Action;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Storage;

class RegenerateResumeWidget extends Widget
{
    protected static ?int $sort = 20;

    protected string $view = 'filament.widgets.regenerate-resume-widget';

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('regenerate')
                ->label(__('Regenerate PDF Cache'))
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    // Wipe all cached PDFs.
                    Storage::disk('local')->deleteDirectory('resume/pdf');

                    // Dispatch a job for every locale × template combination.
                    $locales = array_keys(config('laravellocalization.supportedLocales', ['en' => [], 'fr' => []]));
                    $registry = app(TemplateRegistry::class);
                    $templates = $registry->slugs();

                    foreach ($locales as $locale) {
                        foreach ($templates as $template) {
                            GenerateResumePdf::dispatch($locale, $template);
                        }
                    }
                }),
        ];
    }

    /**
     * Count of cached PDF files currently on disk.
     */
    public function cachedCount(): int
    {
        return count(Storage::disk('local')->allFiles('resume/pdf'));
    }
}
