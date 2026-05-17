<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        $artists = [
            [
                'name' => 'BLACKPINK',
                'description' => 'BLACKPINK is a South Korean girl group formed by YG Entertainment.',
                'image' => 'artists/blackpink.jpg',
            ],
            [
                'name' => 'BTS',
                'description' => 'BTS is a global K-pop boy group known for powerful performances.',
                'image' => 'artists/bts.jpg',
            ],
            [
                'name' => 'NCT',
                'description' => 'NCT is a K-pop group with multiple sub-units and global concept.',
                'image' => 'artists/nct.jpg',
            ],
            [
                'name' => 'AESPA',
                'description' => 'AESPA is a futuristic K-pop girl group from SM Entertainment.',
                'image' => 'artists/aespa.jpg',
            ],
            [
                'name' => 'TWICE',
                'description' => 'TWICE is a popular girl group known for catchy songs.',
                'image' => 'artists/twice.jpg',
            ],
            [
                'name' => 'SEVENTEEN',
                'description' => 'SEVENTEEN is a self-producing K-pop boy group.',
                'image' => 'artists/seventeen.jpg',
            ],
            [
                'name' => 'EXO',
                'description' => 'EXO is a legendary K-pop boy group from SM Entertainment.',
                'image' => 'artists/exo.jpg',
            ],
            [
                'name' => 'ENHYPEN',
                'description' => 'ENHYPEN is a 4th gen K-pop boy group formed through I-LAND.',
                'image' => 'artists/enhypen.jpg',
            ],
            [
                'name' => 'IVE',
                'description' => 'IVE is a rising girl group known for elegant concepts.',
                'image' => 'artists/ive.jpg',
            ],
            [
                'name' => 'ITZY',
                'description' => 'ITZY is a girl group known for confident and powerful concept.',
                'image' => 'artists/itzy.jpg',
            ],
            [
                'name' => 'NEWJEANS',
                'description' => 'NewJeans is a trendy girl group with Y2K concept.',
                'image' => 'artists/newjeans.jpg',
            ],
            [
                'name' => 'STRAY KIDS',
                'description' => 'Stray Kids is a self-producing boy group with intense music style.',
                'image' => 'artists/straykids.jpg',
            ],
        ];

        foreach ($artists as $artist) {
            Artist::updateOrCreate(
                ['name' => $artist['name']],
                $artist
            );
        }
    }
}