<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\LanguageSpoken;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Services\Home\HomeLayoutRegistry;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private HomeLayoutRegistry $layouts) {}

    public function __invoke(): View
    {
        $profile = Cache::remember('home:profile', now()->addDay(), fn () => Profile::first());
        $projects = Cache::remember(
            'home:featured_projects',
            now()->addHour(),
            fn () => Project::orderBy('order')->with('media')->get(),
        );
        $experiences = Experience::orderByDesc('start_date')->get();
        $educations = Education::orderByDesc('start_date')->get();
        $skills = Skill::orderBy('order')->get();
        $certifications = Certification::orderByDesc('issued_at')->with('media')->get();

        $stats = [
            'projects' => $projects->count(),
            'years' => max(1, now()->year - ($experiences->min('start_date')?->year ?? now()->year)),
            'certifications' => $certifications->count(),
            'languages' => LanguageSpoken::count(),
        ];

        $view = $this->layouts->view(SiteSetting::instance()->home_layout);

        return view($view, compact('profile', 'projects', 'experiences', 'educations', 'skills', 'certifications', 'stats'));
    }
}
