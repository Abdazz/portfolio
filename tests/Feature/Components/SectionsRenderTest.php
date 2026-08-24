<?php

use App\Models\Certification;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Support\Facades\Blade;

it('renders the hero with the profile name', function () {
    $profile = Profile::factory()->create(['full_name' => 'Ada Lovelace']);
    $html = Blade::render('<x-organisms.sections.hero :profile="$profile" />', ['profile' => $profile]);

    expect($html)->toContain('Ada Lovelace');
});

it('renders hero social links stored as a platform=>url map', function () {
    $profile = Profile::factory()->create([
        'social_links' => ['linkedin' => 'https://linkedin.com/in/ada', 'github' => 'https://github.com/ada'],
    ]);
    $html = Blade::render('<x-organisms.sections.hero :profile="$profile" />', ['profile' => $profile]);

    expect($html)
        ->toContain('https://linkedin.com/in/ada')
        ->toContain('https://github.com/ada');
});

it('renders the about section with a stat value', function () {
    $profile = Profile::factory()->create();
    $html = Blade::render(
        '<x-organisms.sections.about :profile="$profile" :stats="$stats" />',
        ['profile' => $profile, 'stats' => ['projects' => 7, 'years' => 3, 'certifications' => 2, 'languages' => 2]],
    );

    expect($html)->toContain('7')->toContain(__('home.stat_projects'));
});

it('renders the portfolio grid with a project title', function () {
    $projects = Project::factory()->count(2)->create();
    $html = Blade::render('<x-organisms.sections.portfolio :projects="$projects" />', ['projects' => $projects]);

    expect($html)->toContain($projects->first()->getTranslation('title', 'en'));
});

it('renders the experience section with a role title', function () {
    $experiences = Experience::factory()->count(2)->create();
    $html = Blade::render('<x-organisms.sections.experience :experiences="$experiences" />', ['experiences' => $experiences]);

    expect($html)->toContain($experiences->first()->getTranslation('title', 'en'));
});

it('renders the skills section with a skill name', function () {
    $skills = Skill::factory()->count(3)->create();
    $html = Blade::render('<x-organisms.sections.skills :skills="$skills" />', ['skills' => $skills]);

    expect($html)->toContain($skills->first()->getTranslation('name', 'en'));
});

it('renders the resume cta linking to the resume route', function () {
    $html = Blade::render('<x-organisms.sections.resume-cta />');

    expect($html)->toContain(route('resume'))->toContain(__('home.resume_title'));
});

it('renders the certifications marquee with each certification title', function () {
    $certs = Certification::factory()->count(3)->create();
    $html = Blade::render('<x-organisms.sections.certifications-marquee :certifications="$certs" />', ['certs' => $certs]);

    foreach ($certs as $cert) {
        expect($html)->toContain($cert->getTranslation('title', 'en'));
    }
});

it('renders the contact section with the embedded contact form', function () {
    $html = Blade::render('<x-organisms.sections.contact-cta />');

    expect($html)->toContain(__('home.contact_title'))->toContain('wire:name="contact-form"');
});

it('renders decorative text with the passed text', function () {
    $html = Blade::render('<x-organisms.sections.decorative-text text="PORTFOLIO" />');

    expect($html)->toContain('PORTFOLIO');
});
