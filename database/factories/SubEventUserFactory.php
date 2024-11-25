<?php
namespace Database\Factories;

use App\Models\SubEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class SubEventUserFactory extends Factory
{
    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>|null
     */
    public function definition(): ?array
    {
        // Seleciona um subevento aleatório
        $subEvent = SubEvent::inRandomOrder()->first();

        // Retorna null se não houver subeventos
        if (!$subEvent) {
            return null;
        }

        // Seleciona um usuário aleatório
        $user = User::inRandomOrder()->first();

        // Verifica se já existe a associação na tabela pivot
        $exists = DB::table('sub_event_user')
            ->where('sub_event_id', $subEvent->id)
            ->where('user_id', $user->id)
            ->exists();

        // Retorna null se a associação já existir
        if ($exists) {
            return null;
        }

        return [
            'sub_event_id' => $subEvent->id,
            'user_id' => $user->id,
        ];
    }

    /**
     * Cria diretamente na tabela pivot.
     *
     * @param array $attributes
     */
    public function createPivot(array $attributes = [])
    {
        $data = $this->definition();

        // Se não houver dados válidos, aborta a criação
        if ($data === null) {
            return;
        }

        // Mescla os dados com os atributos fornecidos
        $data = array_merge($data, $attributes);

        // Insere diretamente na tabela pivot
        DB::table('sub_event_user')->insert($data);
    }
}
