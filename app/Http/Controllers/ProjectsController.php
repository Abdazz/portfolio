<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\JsonQuery;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function index(): View
    {
        return view('projects.index');
    }

    public function show(string $slug): View
    {
        $locale = app()->getLocale();

        $project = Cache::remember(
            "project:show:{$locale}:{$slug}",
            now()->addHour(),
            fn () => Project::whereRaw(JsonQuery::extract('slug', $locale).' = ?', [$slug])->firstOrFail(),
        );

        return view('projects.show', compact('project'));
    }
}
