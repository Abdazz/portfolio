<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $titleEn = fake('en_US')->catchPhrase();
        $titleFr = fake('fr_FR')->catchPhrase();

        return [
            'title' => ['en' => $titleEn, 'fr' => $titleFr],
            'slug' => [
                'en' => Str::slug($titleEn).'-'.fake()->unique()->numerify('##'),
                'fr' => Str::slug($titleFr).'-'.fake()->unique()->numerify('##'),
            ],
            'summary' => ['en' => fake('en_US')->sentence(12), 'fr' => fake('fr_FR')->sentence(12)],
            'body' => ['en' => fake('en_US')->paragraphs(3, true), 'fr' => fake('fr_FR')->paragraphs(3, true)],
            'tech_stack' => fake()->randomElements(['Laravel', 'Vue', 'React', 'PostgreSQL', 'Docker', 'Tailwind', 'TypeScript', 'Python', 'Livewire', 'Alpine.js'], fake()->numberBetween(2, 5)),
            'links' => [
                ['label' => 'GitHub', 'url' => 'https://github.com/example/'.Str::slug($titleEn)],
            ],
            'featured' => fake()->boolean(30),
            'order' => fake()->numberBetween(0, 20),
        ];
    }

    public function featured(): static
    {
        return $this->state(['featured' => true]);
    }
}
