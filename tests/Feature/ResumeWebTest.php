<?php

use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

/**
 * @return list<class-string>
 */
function resumeWebLocaleMiddleware(): array
{
    return [
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ];
}

it('renders the résumé web page with export links', function () {
    $this->withoutMiddleware(resumeWebLocaleMiddleware())
        ->get('/resume')
        ->assertOk()
        ->assertSee(__('resume.title'))
        ->assertSee(route('resume.print'), false)
        ->assertSee(route('resume.download'), false)
        ->assertSee(route('resume.json'), false);
});
