<?php

use App\Filament\Resources\Experiences\Pages\CreateExperience;
use App\Filament\Resources\Experiences\Pages\EditExperience;
use App\Models\Experience;
use App\Models\User;
use Livewire\Livewire;

// ─── Helpers ─────────────────────────────────────────────────────────────────

function enrolledAdmin(): User
{
    return User::factory()->create(['app_authentication_secret' => 'JBSWY3DPEHPK3PXP']);
}

// ─── Create ───────────────────────────────────────────────────────────────────

test('admin can create an experience via the Filament resource', function () {
    $this->actingAs(enrolledAdmin());

    Livewire::test(CreateExperience::class)
        ->fillForm([
            'title' => 'Software Engineer',
            'company' => 'Acme Corp',
            'location' => 'Paris, France',
            'start_date' => '2020-01-01',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('experiences', ['company' => 'Acme Corp']);
});

test('create experience validates required fields', function () {
    $this->actingAs(enrolledAdmin());

    Livewire::test(CreateExperience::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['title', 'company', 'start_date']);
});

// ─── Update ───────────────────────────────────────────────────────────────────

test('admin can update an experience via the Filament resource', function () {
    $this->actingAs(enrolledAdmin());

    $experience = Experience::factory()->create(['company' => 'OldCo']);

    Livewire::test(EditExperience::class, ['record' => $experience->id])
        ->fillForm(['company' => 'NewCo'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('experiences', ['id' => $experience->id, 'company' => 'NewCo']);
});

// ─── Delete ───────────────────────────────────────────────────────────────────

test('admin can delete an experience via the edit page', function () {
    $this->actingAs(enrolledAdmin());

    $experience = Experience::factory()->create();

    Livewire::test(EditExperience::class, ['record' => $experience->id])
        ->callAction('delete');

    $this->assertSoftDeleted('experiences', ['id' => $experience->id]);
});
