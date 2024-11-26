<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccommodationsSeeder extends Seeder
{
    public function run()
    {
        DB::table('accommodations')->insert([
            ['accommodation' => 'Sencilla', 'created_at' => now(), 'updated_at' => now()],
            ['accommodation' => 'Doble', 'created_at' => now(), 'updated_at' => now()],
            ['accommodation' => 'Triple', 'created_at' => now(), 'updated_at' => now()],
            ['accommodation' => 'Cuádruple', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}