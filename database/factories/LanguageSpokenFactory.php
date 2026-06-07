<?php

namespace Database\Factories;

use App\Models\LanguageSpoken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LanguageSpoken>
 */
class LanguageSpokenFactory extends Factory
{
    public function definition(): array
    {
        $languages = [
            ['en' => 'English', 'fr' => 'Anglais'],
            ['en' => 'French', 'fr' => 'Français'],
            ['en' => 'Spanish', 'fr' => 'Espagnol'],
            ['en' => 'German', 'fr' => 'Allemand'],
            ['en' => 'Arabic', 'fr' => 'Arabe'],
        ];

        return [
            'name' => fake()->randomElement($languages),
            'level' => fake()->randomElement(['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'native']),
        ];
    }
}
