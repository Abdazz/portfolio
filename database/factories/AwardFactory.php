<?php

namespace Database\Factories;

use App\Models\Award;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Award>
 */
class AwardFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement(['Founder Institute Programme', 'GenieTIC Winner', 'AFIDBA Winner', 'POESAM Laureate']);

        return [
            'title' => ['en' => $title, 'fr' => $title],
            'issuer' => fake()->company(),
            'awarded_at' => fake()->dateTimeBetween('-10 years', 'now'),
            'url' => fake()->optional(0.3)->url(),
        ];
    }
}
