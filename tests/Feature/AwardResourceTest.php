<?php

use App\Filament\Resources\Awards\Pages\CreateAward;
use App\Filament\Resources\Awards\Pages\EditAward;
use App\Models\Award;
use App\Models\User;
use Livewire\Livewire;

test('admin can create an award via the Filament resource', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    Livewire::test(CreateAward::class)
        ->fillForm([
            'title' => 'GenieTIC Winner',
            'issuer' => 'GenieTIC Burkina Faso',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('awards', ['issuer' => 'GenieTIC Burkina Faso']);
});

test('create award validates required fields', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    Livewire::test(CreateAward::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['title', 'issuer']);
});

test('admin can update an award via the Filament resource', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $award = Award::factory()->create(['issuer' => 'OldOrg']);

    Livewire::test(EditAward::class, ['record' => $award->id])
        ->fillForm(['issuer' => 'NewOrg'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('awards', ['id' => $award->id, 'issuer' => 'NewOrg']);
});

test('admin can delete an award via the edit page', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $award = Award::factory()->create();

    Livewire::test(EditAward::class, ['record' => $award->id])
        ->callAction('delete');

    $this->assertDatabaseMissing('awards', ['id' => $award->id]);
});
