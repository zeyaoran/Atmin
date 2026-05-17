<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $events = [1, 2, 3];

        foreach ($events as $eventId) {

            // VIP
            Ticket::create([
                'artist_id' => 1,
                'event_id'  => $eventId,
                'category'  => 'VIP',
                'price'     => 2500000,
                'stock'     => 50,
            ]);

            // CAT1
            Ticket::create([
                'artist_id' => 1,
                'event_id'  => $eventId,
                'category'  => 'CAT1',
                'price'     => 1500000,
                'stock'     => 100,
            ]);

            // CAT2
            Ticket::create([
                'artist_id' => 1,
                'event_id'  => $eventId,
                'category'  => 'CAT2',
                'price'     => 900000,
                'stock'     => 150,
            ]);
        }
    }
}