<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'headline' => ['en' => fake('en_US')->jobTitle(), 'fr' => fake('fr_FR')->jobTitle()],
            'bio' => ['en' => fake('en_US')->paragraph(3), 'fr' => fake('fr_FR')->paragraph(3)],
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city().', '.fake()->country(),
            'social_links' => ['github' => 'https://github.com/'.fake()->userName()],
        ];
    }
}
