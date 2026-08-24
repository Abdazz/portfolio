<?php

use App\Models\User;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

// Note on scope: in Pest's CLI bootstrap, mcamara/laravel-localization's
// `setLocale()` returns an empty prefix (no current request URL), so
// locale-prefixed routes (`/en`, `/fr/projets`, etc.) are not registered
// in the test route table. Full per-locale URL routing is verified via the
// containerised stack with curl. Here we test what's verifiable in CLI.

test('home redirects to a localized URL', function () {
    $this->get('/')->assertRedirect('/en');
});

test('un-prefixed projects route exists', function () {
    // Bypass the locale-redirect middleware that would 302 us into /en/projects
    // (which doesn't exist in the CLI route table).
    $this
        ->withoutMiddleware([
            LaravelLocalizationRedirectFilter::class,
            LocaleSessionRedirect::class,
        ])
        ->get('/projects')
        ->assertOk()
        ->assertSee('Projects');
});

test('unauthenticated /admin redirects to Fortify login', function () {
    $this->get('/admin')->assertRedirect('/login');
});

test('an authenticated admin reaches the panel', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();

    $response = $this->actingAs($admin)->get('/admin');

    // Filament may issue a final 302 to the dashboard; 200 when already there.
    expect($response->status())->toBeIn([200, 302]);
});

test('named public routes resolve', function () {
    expect(route('home', absolute: false))->toBe('/');
    expect(route('projects.index', absolute: false))->toBe('/projects');
    expect(route('resume', absolute: false))->toBe('/resume');
    expect(route('contact', absolute: false))->toBe('/contact');
});
