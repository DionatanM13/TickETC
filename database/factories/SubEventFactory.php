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
        // Geração de datas e horários consistentes
        $date = fake()->dateTimeBetween('now', '+1 year'); // Data inicial
        $startTime = fake()->time();
        $finalTime = fake()->time('H:i', strtotime('+1 hour', strtotime($startTime))); // Garantir que seja depois de startTime

        return [
            'date' => $date->format('Y-m-d'), // Data formatada
            'time' => $startTime, // Horário inicial
            'finalTime' => $finalTime, // Horário final
            'title' => fake()->sentence(3), // Título realista, curto e descritivo
            'description' => fake()->paragraphs(2, true), // Descrição mais longa e detalhada
            'local' => fake()->streetAddress(), // Local com endereço realista
            'size' => fake()->numberBetween(10, 500), // Tamanho entre 10 e 500 participantes
            'event_id' => Event::all()->random()->id, // Seleciona um evento existente
        ];
    }
}
