<?php

use Illuminate\Testing\TestResponse;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

function visitLayoutHome(): TestResponse
{
    return test()->withoutMiddleware([
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ])->get('/');
}

it('renders the public shell with hreflang alternates', function () {
    visitLayoutHome()->assertOk()
        ->assertSee('hreflang="fr"', false)
        ->assertSee('hreflang="x-default"', false);
});

it('renders the primary navigation and footer', function () {
    $response = visitLayoutHome()->assertOk();

    $response->assertSee(route('projects.index'), false)
        ->assertSee(route('contact'), false)
        ->assertSee('id="main-content"', false);
});
