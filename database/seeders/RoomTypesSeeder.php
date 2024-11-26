<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypesSeeder extends Seeder
{
    public function run()
    {
        DB::table('room_types')->insert([
            ['type' => 'Estándar', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Junior', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Suite', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
