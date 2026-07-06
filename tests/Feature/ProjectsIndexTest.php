<?php

use App\Models\Project;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

/**
 * @return list<class-string>
 */
function projectsIndexLocaleMiddleware(): array
{
    return [
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ];
}

it('lists projects on the Gerold index', function () {
    $project = Project::factory()->create();

    $this->withoutMiddleware(projectsIndexLocaleMiddleware())
        ->get('/projects')
        ->assertOk()
        ->assertSee($project->getTranslation('title', 'en'))
        ->assertSee(__('projects.title'));
});
