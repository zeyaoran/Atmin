<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'artist_name' => 'BLACKPINK',
                'title' => 'BLACKPINK WORLD TOUR',
                'category' => 'World Tour',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'status' => 'HOT',
                'date' => '2026-06-12 19:00:00',
                'price' => 1500000,
                'image' => 'https://images.unsplash.com/photo-1503095396549-807759245b35',
            ],
            [
                'artist_name' => 'BLACKPINK',
                'title' => 'BLACKPINK SEOUL ENCORE',
                'category' => 'Concert',
                'city' => 'Seoul',
                'country' => 'Korea',
                'status' => 'NEW',
                'date' => '2026-09-02 19:30:00',
                'price' => 1650000,
                'image' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14',
            ],
            [
                'artist_name' => 'BTS',
                'title' => 'BTS WORLD STAGE',
                'category' => 'World Tour',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'status' => 'HOT',
                'date' => '2026-07-20 18:30:00',
                'price' => 1750000,
                'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819',
            ],
        ];

        foreach ($events as $event) {

            $artist = Artist::where('name', $event['artist_name'])->first();

            Event::updateOrCreate(
                ['title' => $event['title']],
                [
                    'artist_id' => $artist?->id,
                    'title' => $event['title'],
                    'category' => $event['category'],
                    'city' => $event['city'],
                    'country' => $event['country'],
                    'status' => $event['status'],
                    'date' => $event['date'],
                    'price' => $event['price'],
                    'image' => $event['image'],
                ]
            );
        }
    }
}