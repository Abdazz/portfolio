<?php

use App\Models\SiteSetting;
use Illuminate\Testing\TestResponse;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

function visitHome(): TestResponse
{
    return test()->withoutMiddleware([
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ])->get('/');
}

it('renders the layout selected in settings', function () {
    SiteSetting::instance()->update(['home_layout' => 'gerold-01']);

    visitHome()->assertOk()->assertViewIs('home.layouts.gerold-01');
});

it('falls back to gerold-01 for an unknown layout', function () {
    SiteSetting::instance()->update(['home_layout' => 'bogus']);

    visitHome()->assertOk()->assertViewIs('home.layouts.gerold-01');
});
