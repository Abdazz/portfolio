<?php

use App\Filament\Resources\Education\Pages\CreateEducation;
use App\Filament\Resources\Education\Pages\EditEducation;
use App\Models\Education;
use App\Models\User;
use Livewire\Livewire;

// ─── Create ───────────────────────────────────────────────────────────────────

test('admin can create an education entry via the Filament resource', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    Livewire::test(CreateEducation::class)
        ->fillForm([
            'institution' => 'MIT',
            'degree' => 'Bachelor',
            'start_date' => '2015-09-01',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('education', ['institution' => 'MIT']);
});

test('create education validates required fields', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    Livewire::test(CreateEducation::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['institution', 'degree', 'start_date']);
});

// ─── Update ───────────────────────────────────────────────────────────────────

test('admin can update an education entry via the Filament resource', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    $education = Education::factory()->create(['institution' => 'Old University']);

    Livewire::test(EditEducation::class, ['record' => $education->id])
        ->fillForm(['institution' => 'New University'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('education', ['id' => $education->id, 'institution' => 'New University']);
});

// ─── Delete ───────────────────────────────────────────────────────────────────

test('admin can delete an education entry via the edit page', function () {
    $admin = User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
    $this->actingAs($admin);

    $education = Education::factory()->create();

    Livewire::test(EditEducation::class, ['record' => $education->id])
        ->callAction('delete');

    $this->assertSoftDeleted('education', ['id' => $education->id]);
});
