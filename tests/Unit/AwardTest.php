<?php

use App\Models\Award;
use Carbon\CarbonImmutable;

it('stores a translatable title', function () {
    $award = Award::factory()->create([
        'title' => ['en' => 'GenieTIC Winner', 'fr' => 'Lauréat GenieTIC'],
    ]);

    app()->setLocale('fr');

    expect($award->getTranslation('title', 'fr'))->toBe('Lauréat GenieTIC')
        ->and($award->getTranslation('title', 'en'))->toBe('GenieTIC Winner');
});

it('casts awarded_at to a date', function () {
    $award = Award::factory()->create(['awarded_at' => '2020-09-01']);

    expect($award->awarded_at)->toBeInstanceOf(CarbonImmutable::class);
});
