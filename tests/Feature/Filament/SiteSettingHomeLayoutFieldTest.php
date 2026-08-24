<?php

use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;
use App\Models\SiteSetting;
use App\Models\User;
use Livewire\Livewire;

test('the site settings form exposes the home_layout select', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $record = SiteSetting::instance();

    Livewire::test(EditSiteSetting::class, ['record' => $record->getKey()])
        ->assertFormFieldExists('home_layout');
});

test('admin can save a home_layout selection', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $record = SiteSetting::instance();

    Livewire::test(EditSiteSetting::class, ['record' => $record->getKey()])
        ->fillForm(['home_layout' => 'gerold-08'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SiteSetting::instance()->fresh()->home_layout)->toBe('gerold-08');
});
