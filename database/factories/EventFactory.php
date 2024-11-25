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
    // Certifique-se de que o diretório de imagens existe
    $path = public_path('img/events');
    if (!is_dir($path)) {
        mkdir($path, 0777, true); // Cria o diretório se não existir
    }

    $files = File::files($path);
    $randomImage = count($files) > 0 ? $files[array_rand($files)]->getFilename() : 'default.jpg'; // Use uma imagem padrão, se nenhuma estiver disponível

    // Geração de datas consistentes
    $startDate = fake()->dateTimeBetween('now', '+1 year');
    if (!$startDate instanceof \DateTime) {
        $startDate = new \DateTime($startDate); // Converte em DateTime caso não seja
    }
    $otherDate = $startDate->modify('+1 week');

    $endDate = fake()->dateTimeBetween($startDate, $otherDate);
    $chance = fake()->numberBetween(0,1);

    // Configuração para eventos privados com domínio
    $isPrivate = fake()->boolean(30); // 30% de chance de ser privado
    $dominio = $isPrivate ? fake()->domainName() : null;

    return [
        'title' => fake()->sentence(3, true), // Título mais realista
        'date' => $startDate->format('Y-m-d'),
        'time' => $startDate->format('H:i:s'),
        'finalDate' => $chance ? $endDate->format('Y-m-d') : null,
        'city' => fake()->city(),
        'local' => fake()->streetAddress(),
        'size' => fake()->numberBetween(10, 1000), // Garantindo o limite de tamanho >= 1
        'private' => $isPrivate,
        'dominio' => $dominio,
        'description' => fake()->paragraphs(3, true), // Descrição mais longa e detalhada
        'categories' =>fake()->randomElements(['Feira', 'Show', 'Educativo', 'Esportivo', 'Reunião', 'Palestra'], fake()->numberBetween(1, 3)),
        'image' => $randomImage,
        'user_id' => User::all()->random()->id, // Garante que exista ao menos 1 usuário
    ];
}

}
