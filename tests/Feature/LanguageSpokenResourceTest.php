<?php

use App\Filament\Resources\LanguageSpokens\Pages\CreateLanguageSpoken;
use App\Filament\Resources\LanguageSpokens\Pages\EditLanguageSpoken;
use App\Models\LanguageSpoken;
use App\Models\User;
use Livewire\Livewire;

// ─── Create ───────────────────────────────────────────────────────────────────

test('admin can create a language spoken via the Filament resource', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    Livewire::test(CreateLanguageSpoken::class)
        ->fillForm([
            'name' => 'English',
            'level' => 'native',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('languages_spoken', ['level' => 'native']);
});

test('create language spoken validates required fields', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    Livewire::test(CreateLanguageSpoken::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['name', 'level']);
});

// ─── Update ───────────────────────────────────────────────────────────────────

test('admin can update a language spoken via the Filament resource', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    $language = LanguageSpoken::factory()->create(['level' => 'B1']);

    Livewire::test(EditLanguageSpoken::class, ['record' => $language->id])
        ->fillForm(['level' => 'C2'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('languages_spoken', ['id' => $language->id, 'level' => 'C2']);
});

// ─── Delete ───────────────────────────────────────────────────────────────────

test('admin can delete a language spoken via the edit page', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    $language = LanguageSpoken::factory()->create();

    Livewire::test(EditLanguageSpoken::class, ['record' => $language->id])
        ->callAction('delete');

    $this->assertDatabaseMissing('languages_spoken', ['id' => $language->id]);
});
