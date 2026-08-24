<?php

use App\Filament\Resources\Certifications\Pages\CreateCertification;
use App\Filament\Resources\Certifications\Pages\EditCertification;
use App\Models\Certification;
use App\Models\User;
use Livewire\Livewire;

// ─── Create ───────────────────────────────────────────────────────────────────

test('admin can create a certification via the Filament resource', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    Livewire::test(CreateCertification::class)
        ->fillForm([
            'title' => 'AWS Certified Developer',
            'issuer' => 'Amazon',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('certifications', ['issuer' => 'Amazon']);
});

test('create certification validates required fields', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    Livewire::test(CreateCertification::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['title', 'issuer']);
});

// ─── Update ───────────────────────────────────────────────────────────────────

test('admin can update a certification via the Filament resource', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $certification = Certification::factory()->create(['issuer' => 'OldCorp']);

    Livewire::test(EditCertification::class, ['record' => $certification->id])
        ->fillForm(['issuer' => 'NewCorp'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('certifications', ['id' => $certification->id, 'issuer' => 'NewCorp']);
});

// ─── Delete ───────────────────────────────────────────────────────────────────

test('admin can delete a certification via the edit page', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $certification = Certification::factory()->create();

    Livewire::test(EditCertification::class, ['record' => $certification->id])
        ->callAction('delete');

    $this->assertDatabaseMissing('certifications', ['id' => $certification->id]);
});
