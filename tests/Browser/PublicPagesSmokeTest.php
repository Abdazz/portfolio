<?php

use App\Models\Profile;
use App\Models\Project;

it('redirects root to default locale', function () {
    $page = visit('/');

    $page->assertPathContains('/en');
});

it('renders the English homepage without JS errors', function () {
    $page = visit('/en');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('renders the French homepage without JS errors', function () {
    $page = visit('/fr');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('renders hreflang tags on the English homepage', function () {
    $page = visit('/en');

    $page->assertSourceHas('hreflang');
});

it('renders the English projects page without JS errors', function () {
    $page = visit('/en/projects');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('renders the French projects page without JS errors', function () {
    $page = visit('/fr/projets');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('renders the English resume page without JS errors', function () {
    $page = visit('/en/resume');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('renders the French CV page without JS errors', function () {
    $page = visit('/fr/cv');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('renders the contact page in English without JS errors', function () {
    $page = visit('/en/contact');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('renders the contact page in French without JS errors', function () {
    $page = visit('/fr/contact');

    $page->assertNoJavaScriptErrors()
        ->assertSeeAnythingIn('body');
});

it('redirects /admin to login when unauthenticated', function () {
    $page = visit('/admin');

    $page->assertPathContains('/login');
});

it('shows the featured project on the homepage when one exists', function () {
    $project = Project::factory()->create([
        'title' => ['en' => 'My Test Project', 'fr' => 'Mon Projet Test'],
        'featured' => true,
        'order' => 1,
    ]);

    $page = visit('/en');

    $page->assertSee('My Test Project');
});

it('shows the profile name on the homepage when a profile exists', function () {
    Profile::factory()->create([
        'full_name' => 'Jane Doe',
    ]);

    $page = visit('/en');

    $page->assertSee('Jane Doe');
});
