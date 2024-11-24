<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(4, true),
            'date' => fake()->date(),
            'time' => fake()->time(),
            'finalDate' => null,
            'city' => fake()->city(),
            'local' => fake()->address(),
            'size' => fake()->randomElement(['grande', 'pequeno', 'medio']),
            'private' => 0,
            'dominio' => null,
            'description' => fake()->text(),
            'categories' => fake()->randomElements(['Feira', 'Show', 'Educativo', 'Esportivo'], 2),
            'image' => fake()->imageUrl(),
            'user_id' => User::all()->random()->id
        ];
    }
}
