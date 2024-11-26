<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeAccommodationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Estándar: Sencilla, Doble
            ['room_type_id' => 1, 'accommodation_id' => 1],
            ['room_type_id' => 1, 'accommodation_id' => 2],

            // Junior: Triple, Cuádruple
            ['room_type_id' => 2, 'accommodation_id' => 3],
            ['room_type_id' => 2, 'accommodation_id' => 4],

            // Suite: Sencilla, Doble, Triple
            ['room_type_id' => 3, 'accommodation_id' => 1],
            ['room_type_id' => 3, 'accommodation_id' => 2],
            ['room_type_id' => 3, 'accommodation_id' => 3],
        ];

        foreach ($data as $relation) {
            DB::table('room_type_accommodation')->insert(array_merge($relation, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}