<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'batch' => fake()->numberBetween(1,5),
            'price' => fake()->numberBetween(50, 500),
            'quantity' => fake()->numberBetween(10, 100),
            'description' => fake()->text(50),
            'event_id' => Event::all()->random()->id
        ];
    }
}
