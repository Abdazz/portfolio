<?php

use App\Models\User;

// ─── Unauthenticated redirects ────────────────────────────────────────────────

test('unauthenticated requests to admin resources are redirected to login', function (string $url) {
    $this->get($url)->assertRedirect('/login');
})->with([
    'profile edit' => ['/admin/profiles/1/edit'],
    'experience index' => ['/admin/experiences'],
    'experience create' => ['/admin/experiences/create'],
    'education index' => ['/admin/education'],
    'education create' => ['/admin/education/create'],
    'skills index' => ['/admin/skills'],
    'skills create' => ['/admin/skills/create'],
    'certifications index' => ['/admin/certifications'],
    'certifications create' => ['/admin/certifications/create'],
    'language-spokens index' => ['/admin/language-spokens'],
    'language-spokens create' => ['/admin/language-spokens/create'],
]);

// ─── Authenticated access ─────────────────────────────────────────────────────

test('authenticated enrolled admin can view the experiences index', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();

    $this->actingAs($admin)
        ->get('/admin/experiences')
        ->assertSuccessful();
});

test('authenticated enrolled admin can view the education index', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();

    $this->actingAs($admin)
        ->get('/admin/education')
        ->assertSuccessful();
});

test('authenticated enrolled admin can view the skills index', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();

    $this->actingAs($admin)
        ->get('/admin/skills')
        ->assertSuccessful();
});

test('authenticated enrolled admin can view the certifications index', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();

    $this->actingAs($admin)
        ->get('/admin/certifications')
        ->assertSuccessful();
});

test('authenticated enrolled admin can view the language-spokens index', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();

    $this->actingAs($admin)
        ->get('/admin/language-spokens')
        ->assertSuccessful();
});

test('unenrolled admin is forbidden from admin resources', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get('/admin/experiences')
        ->assertForbidden();
});
