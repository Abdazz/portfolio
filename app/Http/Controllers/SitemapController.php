<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = Sitemap::create();

        foreach (['en', 'fr'] as $locale) {
            app()->setLocale($locale);

            $sitemap->add(
                Url::create(route('home'))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(1.0)
            );

            $sitemap->add(
                Url::create(route('projects.index'))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
            );

            $sitemap->add(
                Url::create(route('resume'))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.8)
            );

            $sitemap->add(
                Url::create(route('contact'))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                    ->setPriority(0.5)
            );

            Project::where('featured', true)
                ->orWhere('featured', false)
                ->each(function (Project $project) use ($sitemap, $locale) {
                    $slug = $project->getTranslation('slug', $locale, false);
                    if (! filled($slug)) {
                        return;
                    }

                    $sitemap->add(
                        Url::create(route('projects.show', ['slug' => $slug]))
                            ->setLastModificationDate($project->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.7)
                    );
                });
        }

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
