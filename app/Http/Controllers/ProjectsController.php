<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\JsonQuery;
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

        $project = Project::whereRaw(JsonQuery::extract('slug', $locale).' = ?', [$slug])->firstOrFail();

        return view('projects.show', compact('project'));
    }
}
