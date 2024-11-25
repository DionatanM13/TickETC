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
        $isFree = fake()->boolean(20); // 20% de chance de ser gratuito

        return [
            'title' => fake()->words(3, true), // Título com até 3 palavras
            'batch' => fake()->numberBetween(1, 5), // Lote entre 1 e 5
            'price' => $isFree ? 0 : fake()->randomFloat(2, 10, 500), // Preço zero para ingressos gratuitos, caso contrário, entre 10 e 500
            'quantity' => fake()->numberBetween(10, 1000), // Quantidade entre 10 e 1000 ingressos
            'description' => fake()->paragraph(2), // Descrição mais longa e detalhada
            'event_id' => Event::all()->random()->id, // Associa a um evento existente
        ];
    }
}

