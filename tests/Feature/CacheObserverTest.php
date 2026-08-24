<?php

use App\Models\Profile;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

uses(RefreshDatabase::class);

// ─── ProfileCacheObserver ──────────────────────────────────────────────────────

test('saving a profile flushes the home:profile cache key', function () {
    $profile = Profile::factory()->create();
    Cache::put('home:profile', $profile, now()->addHour());

    $profile->update(['full_name' => 'Updated Name']);

    expect(Cache::has('home:profile'))->toBeFalse();
});

test('deleting a profile flushes the home:profile cache key', function () {
    $profile = Profile::factory()->create();
    Cache::put('home:profile', $profile, now()->addHour());

    $profile->delete();

    expect(Cache::has('home:profile'))->toBeFalse();
});

// ─── ProjectCacheObserver ──────────────────────────────────────────────────────

test('saving a project flushes home:featured_projects and its slug keys', function () {
    $project = Project::factory()->create([
        'slug' => ['en' => 'my-project', 'fr' => 'mon-projet'],
    ]);

    Cache::put('home:featured_projects', collect([$project]), now()->addHour());
    Cache::put('project:show:en:my-project', $project, now()->addHour());
    Cache::put('project:show:fr:mon-projet', $project, now()->addHour());

    $project->update(['featured' => false]);

    expect(Cache::has('home:featured_projects'))->toBeFalse()
        ->and(Cache::has('project:show:en:my-project'))->toBeFalse()
        ->and(Cache::has('project:show:fr:mon-projet'))->toBeFalse();
});

test('deleting a project flushes home:featured_projects and its slug keys', function () {
    $project = Project::factory()->create([
        'slug' => ['en' => 'del-project', 'fr' => 'del-projet'],
    ]);

    Cache::put('home:featured_projects', collect([$project]), now()->addHour());
    Cache::put('project:show:en:del-project', $project, now()->addHour());
    Cache::put('project:show:fr:del-projet', $project, now()->addHour());

    $project->delete();

    expect(Cache::has('home:featured_projects'))->toBeFalse()
        ->and(Cache::has('project:show:en:del-project'))->toBeFalse()
        ->and(Cache::has('project:show:fr:del-projet'))->toBeFalse();
});

test('changing a project slug also flushes the old slug cache keys', function () {
    $project = Project::factory()->create([
        'slug' => ['en' => 'old-slug', 'fr' => 'ancien-slug'],
    ]);

    Cache::put('project:show:en:old-slug', $project, now()->addHour());
    Cache::put('project:show:fr:ancien-slug', $project, now()->addHour());

    $project->update([
        'slug' => ['en' => 'new-slug', 'fr' => 'nouveau-slug'],
    ]);

    expect(Cache::has('project:show:en:old-slug'))->toBeFalse()
        ->and(Cache::has('project:show:fr:ancien-slug'))->toBeFalse()
        ->and(Cache::has('project:show:en:new-slug'))->toBeFalse()
        ->and(Cache::has('project:show:fr:nouveau-slug'))->toBeFalse();
});

// ─── Controller caching ────────────────────────────────────────────────────────

test('home page is cached after first request', function () {
    Profile::factory()->create();
    Project::factory()->featured()->create();

    $response = $this->withoutMiddleware([
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ])->get('/');

    $response->assertOk();

    expect(Cache::has('home:featured_projects'))->toBeTrue()
        ->and(Cache::has('home:profile'))->toBeTrue();
});

test('project show page is cached after first request', function () {
    Project::factory()->create([
        'slug' => ['en' => 'cached-project', 'fr' => 'projet-cache'],
    ]);

    $this->withoutMiddleware([
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ])->get('/projects/cached-project')->assertOk();

    expect(Cache::has('project:show:en:cached-project'))->toBeTrue();
});
