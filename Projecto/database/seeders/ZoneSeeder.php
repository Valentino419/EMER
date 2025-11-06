<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('zones')->insert([
            "name"=> "Zona centro",
            "rate" => "1000",
        ]);
        
        DB::table('zones')->insert([
            'name'=> "Zona Norte",
            'rate'=> "1125",
        ]);
        DB::table('zones')->insert([
            "name"=> "Zona Sur",
            "rate"=> "900",
        ]);
    }
}
