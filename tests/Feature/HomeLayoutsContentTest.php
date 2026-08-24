<?php

use App\Models\Project;
use App\Models\SiteSetting;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

it('each layout renders its own view with real data', function (string $slug) {
    Project::factory()->count(2)->create();
    SiteSetting::instance()->update(['home_layout' => $slug]);

    test()->withoutMiddleware([
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ])->get('/')
        ->assertOk()
        ->assertViewIs("home.layouts.$slug");
})->with(['gerold-01', 'gerold-02', 'gerold-03', 'gerold-08', 'gerold-10']);
