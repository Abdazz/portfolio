<?php

namespace Database\Factories;

use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certification>
 */
class CertificationFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement(['AWS Certified Developer', 'Laravel Certified Developer', 'Docker Certified Associate', 'Google Cloud Professional', 'Kubernetes Administrator']);

        return [
            'title' => ['en' => $title, 'fr' => $title],
            'issuer' => fake()->company(),
            'issued_at' => fake()->dateTimeBetween('-5 years', 'now'),
            'credential_url' => fake()->optional(0.6)->url(),
        ];
    }
}
