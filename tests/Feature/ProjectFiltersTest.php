<?php

use App\Livewire\Projects\ProjectFilters;
use App\Models\Project;
use Livewire\Livewire;

// ─── Rendering ───────────────────────────────────────────────────────────────

test('project filters renders with no projects', function () {
    Livewire::test(ProjectFilters::class)
        ->assertOk();
});

// ─── Featured filter ──────────────────────────────────────────────────────────

test('featured filter shows only featured projects', function () {
    Project::factory()->featured()->create([
        'title' => ['en' => 'Featured Project', 'fr' => 'Projet en vedette'],
    ]);

    Project::factory()->create([
        'featured' => false,
        'title' => ['en' => 'Regular Project', 'fr' => 'Projet ordinaire'],
    ]);

    Livewire::test(ProjectFilters::class)
        ->set('filter', 'featured')
        ->assertSee('Featured Project')
        ->assertDontSee('Regular Project');
});

test('all filter shows both featured and regular projects', function () {
    Project::factory()->featured()->create([
        'title' => ['en' => 'Featured One', 'fr' => 'Vedette Un'],
    ]);

    Project::factory()->create([
        'featured' => false,
        'title' => ['en' => 'Regular One', 'fr' => 'Ordinaire Un'],
    ]);

    Livewire::test(ProjectFilters::class)
        ->set('filter', 'all')
        ->assertSee('Featured One')
        ->assertSee('Regular One');
});

// ─── Tech filter ──────────────────────────────────────────────────────────────

test('tech filter shows only projects using the selected technology', function () {
    Project::factory()->create([
        'title' => ['en' => 'Laravel Project', 'fr' => 'Projet Laravel'],
        'tech_stack' => ['Laravel', 'PostgreSQL'],
    ]);

    Project::factory()->create([
        'title' => ['en' => 'Vue Project', 'fr' => 'Projet Vue'],
        'tech_stack' => ['Vue', 'TypeScript'],
    ]);

    Livewire::test(ProjectFilters::class)
        ->set('tech', 'Laravel')
        ->assertSee('Laravel Project')
        ->assertDontSee('Vue Project');
});

// ─── Search filter ────────────────────────────────────────────────────────────

test('search filter matches by project title in current locale', function () {
    Project::factory()->create([
        'title' => ['en' => 'Portfolio Website', 'fr' => 'Site portfolio'],
        'summary' => ['en' => 'A personal site.', 'fr' => 'Un site personnel.'],
    ]);

    Project::factory()->create([
        'title' => ['en' => 'Weather Dashboard', 'fr' => 'Tableau météo'],
        'summary' => ['en' => 'Shows weather data.', 'fr' => 'Affiche la météo.'],
    ]);

    Livewire::test(ProjectFilters::class)
        ->set('search', 'Portfolio')
        ->assertSee('Portfolio Website')
        ->assertDontSee('Weather Dashboard');
});

test('search filter matches by summary', function () {
    Project::factory()->create([
        'title' => ['en' => 'Project Alpha', 'fr' => 'Projet Alpha'],
        'summary' => ['en' => 'Built with Laravel and Livewire.', 'fr' => 'Construit avec Laravel.'],
    ]);

    Project::factory()->create([
        'title' => ['en' => 'Project Beta', 'fr' => 'Projet Beta'],
        'summary' => ['en' => 'A machine learning pipeline.', 'fr' => 'Un pipeline ML.'],
    ]);

    Livewire::test(ProjectFilters::class)
        ->set('search', 'Livewire')
        ->assertSee('Project Alpha')
        ->assertDontSee('Project Beta');
});

test('empty search shows all projects', function () {
    Project::factory()->create(['title' => ['en' => 'Alpha', 'fr' => 'Alpha']]);
    Project::factory()->create(['title' => ['en' => 'Beta', 'fr' => 'Beta']]);

    Livewire::test(ProjectFilters::class)
        ->set('search', '')
        ->assertSee('Alpha')
        ->assertSee('Beta');
});

// ─── Resetting page ───────────────────────────────────────────────────────────

test('changing search resets to page 1', function () {
    // 12 projects → 2 pages (9 per page); navigate to page 2 then search.
    // resetPage() should bring us back to page 1.
    Project::factory()->count(12)->create();

    Livewire::test(ProjectFilters::class)
        ->call('gotoPage', 2)
        ->assertSet('paginators.page', 2)
        ->set('search', 'something')
        ->assertSet('paginators.page', 1);
});
