<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    public function definition(): array
    {
        $skill = fake()->randomElement(['PHP', 'Laravel', 'JavaScript', 'TypeScript', 'Vue.js', 'React', 'Docker', 'PostgreSQL', 'Redis', 'Git', 'Linux', 'Python']);

        return [
            'name' => ['en' => $skill, 'fr' => $skill],
            'category' => fake()->randomElement(['Backend', 'Frontend', 'DevOps', 'Database', 'Tools']),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'icon' => null,
            'order' => fake()->numberBetween(0, 50),
        ];
    }
}
