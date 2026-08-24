<?php

use App\Models\Project;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

// ─── Helpers ─────────────────────────────────────────────────────────────────

/**
 * Returns the middleware classes that redirect to locale-prefixed URLs.
 * Bypassing them lets us test un-prefixed routes in the CLI route table.
 *
 * @return list<class-string>
 */
function localeMiddleware(): array
{
    return [
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ];
}

// ─── Home ─────────────────────────────────────────────────────────────────────

test('home page renders', function () {
    $this->withoutMiddleware(localeMiddleware())
        ->get('/')
        ->assertOk();
});

// ─── Projects ─────────────────────────────────────────────────────────────────

test('projects index renders', function () {
    $this->withoutMiddleware(localeMiddleware())
        ->get('/projects')
        ->assertOk();
});

test('project detail renders for a valid slug', function () {
    Project::factory()->create([
        'slug' => ['en' => 'my-project-01', 'fr' => 'mon-projet-01'],
    ]);

    $this->withoutMiddleware(localeMiddleware())
        ->get('/projects/my-project-01')
        ->assertOk();
});

test('project detail returns 404 for an unknown slug', function () {
    $this->withoutMiddleware(localeMiddleware())
        ->get('/projects/does-not-exist')
        ->assertNotFound();
});

// ─── Resume ───────────────────────────────────────────────────────────────────

test('resume page renders', function () {
    $this->withoutMiddleware(localeMiddleware())
        ->get('/resume')
        ->assertOk();
});

// ─── Contact ──────────────────────────────────────────────────────────────────

test('contact page renders', function () {
    $this->withoutMiddleware(localeMiddleware())
        ->get('/contact')
        ->assertOk();
});
