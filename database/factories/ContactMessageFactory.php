<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->optional(0.7)->sentence(4),
            'message' => fake()->paragraphs(2, true),
            'ip_address' => fake()->ipv4(),
            'locale' => fake()->randomElement(['en', 'fr']),
            'is_read' => fake()->boolean(30),
            'read_at' => null,
        ];
    }

    public function read(): static
    {
        return $this->state([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function unread(): static
    {
        return $this->state([
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}
