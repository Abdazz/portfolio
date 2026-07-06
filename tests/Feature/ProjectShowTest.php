<?php

use App\Models\Project;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;

/**
 * @return list<class-string>
 */
function projectShowLocaleMiddleware(): array
{
    return [
        LaravelLocalizationRedirectFilter::class,
        LocaleSessionRedirect::class,
    ];
}

it('shows a project detail page with its title', function () {
    $project = Project::factory()->create([
        'slug' => ['en' => 'my-cool-project', 'fr' => 'mon-projet-cool'],
    ]);

    $this->withoutMiddleware(projectShowLocaleMiddleware())
        ->get('/projects/my-cool-project')
        ->assertOk()
        ->assertSee($project->getTranslation('title', 'en'))
        ->assertSee(__('projects.back'));
});
