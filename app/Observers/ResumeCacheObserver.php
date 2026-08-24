<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * Invalidates the resume web-view cache whenever any resume model is mutated.
 *
 * Cache keys managed here:
 *   - resume:web:en  (1 h TTL, set by ResumeController)
 *   - resume:web:fr  (1 h TTL, set by ResumeController)
 */
class ResumeCacheObserver
{
    public function saved(mixed $model): void
    {
        $this->clearCache();
    }

    public function deleted(mixed $model): void
    {
        $this->clearCache();
    }

    public function forceDeleted(mixed $model): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        foreach (config('app.supported_locales', ['en', 'fr']) as $locale) {
            Cache::forget("resume:web:{$locale}");
        }
    }
}
