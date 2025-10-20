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
            "name"=> "25 de Mayo",
            "numeration" => "1285",
        ]);
        
        DB::table('zones')->insert([
            'name'=> "Luis N palma",
            'numeration'=> "1125",
        ]);
        DB::table('zones')->insert([
            "name"=> "Zona costanera",
            "numeration"=> "900",
        ]);
    }
}
