<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-10 years', '-2 years');

        return [
            'institution' => fake()->company().' University',
            'degree' => ['en' => fake('en_US')->randomElement(['Bachelor', 'Master', 'PhD']), 'fr' => fake('fr_FR')->randomElement(['Licence', 'Master', 'Doctorat'])],
            'field' => ['en' => fake('en_US')->randomElement(['Computer Science', 'Software Engineering', 'Information Technology']), 'fr' => fake('fr_FR')->randomElement(['Informatique', 'Génie logiciel', 'Technologie de l\'information'])],
            'start_date' => $startDate,
            'end_date' => fake()->optional(0.8)->dateTimeBetween($startDate, 'now'),
            'description' => ['en' => fake('en_US')->paragraph(), 'fr' => fake('fr_FR')->paragraph()],
        ];
    }
}
