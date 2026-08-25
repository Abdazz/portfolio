<?php

use App\Models\Certification;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

it('renders certification titles on the homepage', function () {
    Certification::factory()->create([
        'title' => ['en' => 'AWS Certified Developer', 'fr' => 'Développeur Certifié AWS'],
        'issuer' => 'Amazon',
    ]);

    $response = $this->withoutMiddleware([
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ])->get('/');

    $response->assertOk()
        ->assertSee('AWS Certified Developer')
        ->assertSee('Amazon');
});

it('omits the certifications section when there are none', function () {
    Certification::query()->delete();

    $response = $this->withoutMiddleware([
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ])->get('/');

    $response->assertOk()->assertDontSee('id="certifications"', false);
});
