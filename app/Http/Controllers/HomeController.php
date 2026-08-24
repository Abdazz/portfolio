<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $profile = Profile::first();

        $featuredProjects = Project::where('featured', true)
            ->orderBy('order')
            ->limit(3)
            ->with('media')
            ->get();

        return view('home', compact('profile', 'featuredProjects'));
    }
}
