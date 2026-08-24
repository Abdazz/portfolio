<?php

namespace App\Observers;

use App\Models\Profile;
use Illuminate\Support\Facades\Cache;

/**
 * Invalidates the home-page profile cache whenever the profile record is mutated.
 *
 * Cache key managed here:
 *   - home:profile  (24 h TTL, set by HomeController)
 */
class ProfileCacheObserver
{
    public function saved(Profile $profile): void
    {
        Cache::forget('home:profile');
    }

    public function deleted(Profile $profile): void
    {
        Cache::forget('home:profile');
    }

    public function forceDeleted(Profile $profile): void
    {
        Cache::forget('home:profile');
    }
}
