<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Indah Nurrahma Azzahra',
            'email' => 'zeyaoran127@gmail.com',
            'password' => Hash::make('zeyao17ran127'),
            'image' => 'admins/sylus.jpeg',
        ]);
    }
}