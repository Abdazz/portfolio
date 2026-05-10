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
        ->assertSee('Bilingual portfolio');
});

test('Filament admin login renders', function () {
    $this->get('/admin/login')->assertOk();
});

test('an authenticated user reaches the panel (or the MFA setup redirect)', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)->get('/admin');

    // MFA is required by panel config: a freshly created user is bounced to
    // /admin/multi-factor-authentication/set-up. Either a 200 (already enrolled)
    // or a 302 (must enrol now) is a valid pass for this smoke check.
    expect($response->status())->toBeIn([200, 302]);
});

test('named public routes resolve', function () {
    expect(route('home', absolute: false))->toBe('/');
    expect(route('projects.index', absolute: false))->toBe('/projects');
    expect(route('resume', absolute: false))->toBe('/resume');
    expect(route('contact', absolute: false))->toBe('/contact');
});
