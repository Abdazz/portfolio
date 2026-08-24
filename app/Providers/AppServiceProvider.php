<?php

namespace App\Providers;

use App\Contracts\PdfRenderer;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\LanguageSpoken;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Observers\AuditLogObserver;
use App\Observers\ProfileCacheObserver;
use App\Observers\ProjectCacheObserver;
use App\Observers\ResumeCacheObserver;
use App\Services\Resume\BrowsershotPdfRenderer;
use App\Services\Resume\TemplateRegistry;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TemplateRegistry::class);

        $this->app->bind(PdfRenderer::class, BrowsershotPdfRenderer::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerObservers();
    }

    /**
     * Attach the audit-log observer to every domain model that owns audited writes.
     */
    protected function registerObservers(): void
    {
        $models = [
            Profile::class,
            Experience::class,
            Education::class,
            Skill::class,
            Certification::class,
            LanguageSpoken::class,
        ];

        foreach ($models as $model) {
            $model::observe(AuditLogObserver::class);
            $model::observe(ResumeCacheObserver::class);
        }

        Profile::observe(ProfileCacheObserver::class);
        Project::observe(ProjectCacheObserver::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
