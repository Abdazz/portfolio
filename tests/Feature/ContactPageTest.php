<?php

use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

/**
 * @return list<class-string>
 */
function contactPageLocaleMiddleware(): array
{
    return [
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ];
}

it('renders the Gerold contact page with the form', function () {
    $this->withoutMiddleware(contactPageLocaleMiddleware())
        ->get('/contact')
        ->assertOk()
        ->assertSee(__('contact.title'))
        ->assertSeeLivewire('contact-form');
});
