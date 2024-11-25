<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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

        $path = public_path('img/events');
        if (!is_dir($path)) {
            mkdir($path, 0777, true); // Cria o diretório se não existir
        }

        $files = File::files($path);

        // Escolher um arquivo aleatório da lista, se existirem
        $randomImage = count($files) > 0 ? $files[array_rand($files)]->getFilename() : null;

        return [
            'title' => fake()->words(4, true),
            'date' => fake()->dateTimeBetween('now', '+1 year'),
            'time' => fake()->time(),
            'finalDate' => null,
            'city' => fake()->city(),
            'local' => fake()->address(),
            'size' => fake()->randomElement([200, 600, 1000]),
            'private' => 0,
            'dominio' => null,
            'description' => fake()->text(400),
            'categories' => fake()->randomElements(['Feira', 'Show', 'Educativo', 'Esportivo', 'Reunião', 'Palestra'], 3),
            'image' => $randomImage,
            'user_id' => User::all()->random()->id
        ];
    }
}
