<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

/**
 * Invalidates query-cache entries whenever a project is mutated.
 *
 * Cache keys managed here:
 *   - home:featured_projects  (1 h TTL, set by HomeController)
 *   - project:show:{locale}:{slug}  (1 h TTL, set by ProjectsController)
 *
 * Note: if you switch CACHE_STORE to redis in production you must add
 * App\Models\Project to config/cache.php `serializable_classes`.
 */
class ProjectCacheObserver
{
    public function saved(Project $project): void
    {
        Cache::forget('home:featured_projects');
        $this->forgetProjectKeys($project);
    }

    public function deleted(Project $project): void
    {
        Cache::forget('home:featured_projects');
        $this->forgetProjectKeys($project);
    }

    public function forceDeleted(Project $project): void
    {
        Cache::forget('home:featured_projects');
        $this->forgetProjectKeys($project);
    }

    /**
     * Forget per-locale slug cache keys for the given project.
     * If the slug was changed in this write, the old keys are also forgotten.
     */
    private function forgetProjectKeys(Project $project): void
    {
        foreach (['en', 'fr'] as $locale) {
            $slug = $project->getTranslation('slug', $locale, false);

            if (filled($slug)) {
                Cache::forget("project:show:{$locale}:{$slug}");
            }
        }

        // On slug changes, also bust the pre-save keys.
        if ($project->wasChanged('slug')) {
            $originalRaw = $project->getOriginal('slug');
            /** @var array<string, string> $oldSlugs */
            $oldSlugs = is_array($originalRaw)
                ? $originalRaw
                : json_decode($originalRaw ?? '{}', true);

            foreach (['en', 'fr'] as $locale) {
                $oldSlug = $oldSlugs[$locale] ?? null;

                if (filled($oldSlug)) {
                    Cache::forget("project:show:{$locale}:{$oldSlug}");
                }
            }
        }
    }
}
