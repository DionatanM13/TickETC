<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubEvent>
 */
class SubEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'time' => fake()->time(),
            'title' => fake()->text(20),
            'description' => fake()->text(50),
            'local' => fake()->address(),
            'size' => fake()->randomElement([200, 600, 1000]),
            'event_id' => Event::all()->random()->id
        ];
    }
}
