<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\SubEvent;
use App\Models\EventUserTicket;
use App\Models\SubEventUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria usuários
        User::factory(15)->create();

        // Cria eventos, cada um com 3 tickets
        Event::factory(25)
            ->has(Ticket::factory()->count(3))
            ->create();

        // Cria subeventos
        SubEvent::factory(50)->create();

        // Associa usuários, eventos e tickets
        EventUserTicket::factory(100)->create();

        // Cria um usuário de teste específico
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Associa usuários a subeventos
        $this->seedSubEventUsers();
    }

    /**
     * Associa usuários a subeventos usando lógica personalizada.
     */
    private function seedSubEventUsers(): void
    {
        $users = User::all();
        $subEvents = SubEvent::all();

        foreach ($subEvents as $subEvent) {
            // Seleciona um número aleatório de usuários para cada subevento
            $userCount = fake()->numberBetween(1, 5);
            $randomUsers = $users->random($userCount);

            foreach ($randomUsers as $user) {
                SubEventUser::create([
                    'sub_event_id' => $subEvent->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
