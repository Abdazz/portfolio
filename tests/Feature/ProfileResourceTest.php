<?php

use App\Filament\Resources\Profiles\Pages\EditProfile;
use App\Models\Profile;
use App\Models\User;
use Livewire\Livewire;

// Profile is a singleton resource (edit-only, no create/delete pages).

test('admin can update the profile via the Filament resource', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $profile = Profile::factory()->create(['full_name' => 'Jane Doe']);

    Livewire::test(EditProfile::class, ['record' => $profile->id])
        ->fillForm([
            'full_name' => 'John Smith',
            'headline' => 'Senior Engineer',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('profiles', ['id' => $profile->id, 'full_name' => 'John Smith']);
});

test('update profile validates required fields', function () {
    $admin = User::factory()->withTwoFactorEnabled()->create();
    $this->actingAs($admin);

    $profile = Profile::factory()->create();

    Livewire::test(EditProfile::class, ['record' => $profile->id])
        ->fillForm(['full_name' => '', 'headline' => ''])
        ->call('save')
        ->assertHasFormErrors(['full_name', 'headline']);
});
