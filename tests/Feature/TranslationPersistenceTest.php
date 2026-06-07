<?php

use App\Models\Certification;
use App\Models\Experience;
use App\Models\LanguageSpoken;
use App\Models\Skill;

// Tests that Spatie HasTranslations stores and retrieves per-locale values
// correctly for the JSON columns used in all domain models.

test('experience translatable fields round-trip both locales', function () {
    $experience = Experience::factory()->create([
        'title' => ['en' => 'Software Engineer', 'fr' => 'Ingénieur logiciel'],
        'description' => ['en' => 'Building great things.', 'fr' => 'Construire de belles choses.'],
    ]);

    $fresh = $experience->fresh();

    expect($fresh->getTranslation('title', 'en'))->toBe('Software Engineer')
        ->and($fresh->getTranslation('title', 'fr'))->toBe('Ingénieur logiciel')
        ->and($fresh->getTranslation('description', 'en'))->toBe('Building great things.')
        ->and($fresh->getTranslation('description', 'fr'))->toBe('Construire de belles choses.');
});

test('skill translatable name round-trips both locales', function () {
    $skill = Skill::factory()->create([
        'name' => ['en' => 'Docker', 'fr' => 'Docker'],
    ]);

    $fresh = $skill->fresh();

    expect($fresh->getTranslation('name', 'en'))->toBe('Docker')
        ->and($fresh->getTranslation('name', 'fr'))->toBe('Docker');
});

test('certification translatable title round-trips both locales', function () {
    $cert = Certification::factory()->create([
        'title' => ['en' => 'AWS Developer', 'fr' => 'Développeur AWS'],
    ]);

    $fresh = $cert->fresh();

    expect($fresh->getTranslation('title', 'en'))->toBe('AWS Developer')
        ->and($fresh->getTranslation('title', 'fr'))->toBe('Développeur AWS');
});

test('language spoken translatable name round-trips both locales', function () {
    $lang = LanguageSpoken::factory()->create([
        'name' => ['en' => 'French', 'fr' => 'Français'],
    ]);

    $fresh = $lang->fresh();

    expect($fresh->getTranslation('name', 'en'))->toBe('French')
        ->and($fresh->getTranslation('name', 'fr'))->toBe('Français');
});

test('updating a translation for one locale does not clobber the other', function () {
    $experience = Experience::factory()->create([
        'title' => ['en' => 'Engineer', 'fr' => 'Ingénieur'],
    ]);

    $experience->setTranslation('title', 'en', 'Senior Engineer');
    $experience->save();

    $fresh = $experience->fresh();

    expect($fresh->getTranslation('title', 'en'))->toBe('Senior Engineer')
        ->and($fresh->getTranslation('title', 'fr'))->toBe('Ingénieur');
});

test('setting a missing locale translation returns empty string', function () {
    $experience = Experience::factory()->create([
        'title' => ['en' => 'Engineer'],
    ]);

    expect($experience->getTranslation('title', 'fr', false))->toBe('');
});
