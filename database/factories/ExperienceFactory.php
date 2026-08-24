<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-10 years', '-1 year');

        return [
            'title' => ['en' => fake('en_US')->jobTitle(), 'fr' => fake('fr_FR')->jobTitle()],
            'company' => fake()->company(),
            'location' => fake()->city().', '.fake()->country(),
            'start_date' => $startDate,
            'end_date' => fake()->optional(0.7)->dateTimeBetween($startDate, 'now'),
            'description' => ['en' => fake('en_US')->paragraph(2), 'fr' => fake('fr_FR')->paragraph(2)],
            'order' => fake()->numberBetween(0, 20),
        ];
    }
}
