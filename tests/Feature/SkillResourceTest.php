<?php

use App\Filament\Resources\Skills\Pages\CreateSkill;
use App\Filament\Resources\Skills\Pages\EditSkill;
use App\Models\Skill;
use App\Models\User;
use Livewire\Livewire;

// ─── Create ───────────────────────────────────────────────────────────────────

test('admin can create a skill via the Filament resource', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    Livewire::test(CreateSkill::class)
        ->fillForm([
            'name' => 'Laravel',
            'category' => 'backend',
            'level' => 'expert',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('skills', ['category' => 'backend']);
});

test('create skill validates required fields', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    Livewire::test(CreateSkill::class)
        ->fillForm([])
        ->call('create')
        ->assertHasFormErrors(['name', 'category']);
});

// ─── Update ───────────────────────────────────────────────────────────────────

test('admin can update a skill via the Filament resource', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $skill = Skill::factory()->create(['category' => 'frontend']);

    Livewire::test(EditSkill::class, ['record' => $skill->id])
        ->fillForm(['category' => 'backend'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('skills', ['id' => $skill->id, 'category' => 'backend']);
});

// ─── Delete ───────────────────────────────────────────────────────────────────

test('admin can delete a skill via the edit page', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $skill = Skill::factory()->create();

    Livewire::test(EditSkill::class, ['record' => $skill->id])
        ->callAction('delete');

    $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
});
