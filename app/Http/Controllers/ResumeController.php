<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\LanguageSpoken;
use App\Models\Profile;
use App\Models\Skill;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('resume', [
            'profile' => Profile::first(),
            'experiences' => Experience::orderBy('order')->get(),
            'education' => Education::orderBy('start_date', 'desc')->get(),
            'skills' => Skill::orderBy('order')->get()->groupBy('category'),
            'certifications' => Certification::orderBy('issued_at', 'desc')->get(),
            'languages' => LanguageSpoken::all(),
        ]);
    }
}
