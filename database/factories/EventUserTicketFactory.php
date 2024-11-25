<?php
namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class EventUserTicketFactory extends Factory
{
    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>|null
     */
    public function definition(): array
    {
        $attempts = 0;

        while ($attempts < 3) {
            // Seleciona um evento com tickets disponíveis
            $event = Event::has('tickets')->inRandomOrder()->first();

            // Se não houver eventos com tickets, tenta novamente
            if (!$event) {
                $attempts++;
                continue;
            }

            // Seleciona um ticket associado ao evento
            $ticket = $event->tickets()->inRandomOrder()->first();

            // Seleciona um usuário aleatório
            $user = User::inRandomOrder()->first();

            // Verifica se já existe a associação na tabela pivot
            $exists = DB::table('event_user_ticket')
                ->where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->where('ticket_id', $ticket->id)
                ->exists();

            // Caso já exista, tenta novamente
            if ($exists) {
                $attempts++;
                continue;
            }

            // Se uma combinação válida foi encontrada, retorna os dados
            return [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'ticket_id' => $ticket->id,
            ];
        }

        // Se 3 tentativas falharem, retorna null
        return [];
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
        if (empty($data)) {
            return;
        }

        // Mescla os dados com os atributos fornecidos
        $data = array_merge($data, $attributes);

        // Insere diretamente na tabela pivot
        DB::table('event_user_ticket')->insert($data);
    }
}
