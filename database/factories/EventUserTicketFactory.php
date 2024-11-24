<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\User;
use App\Models\Ticket;

class EventUserTicketFactory extends Factory
{
    public function definition()
    {
        // Seleciona um evento aleatório
        $event = Event::inRandomOrder()->first();

        // Seleciona um ticket pertencente ao evento escolhido
        $ticket = $event->tickets()->inRandomOrder()->first();

        // Seleciona um usuário aleatório
        $user = User::inRandomOrder()->first();

        return [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
        ];
    }

    public function createPivot(array $attributes = [])
    {
        $data = array_merge($this->definition(), $attributes);

        // Inserir diretamente na tabela pivot
        DB::table('event_user_ticket')->insert($data);
    }
    
}
EventUserTicketFactory::new()->createPivot();
