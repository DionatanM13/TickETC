<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventUserTicket;
use App\Models\SubEvent;
use App\Models\Ticket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(15)->create();
        Event::factory(25)->has(Ticket::factory()->count(3))->create();
        SubEvent::factory(50)->create();
        EventUserTicket::factory()->count(100)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
