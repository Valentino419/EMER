<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Car;
class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cars')->insert([
            'user_id'=>'5',
            'car_plate'=>'ARF',
        ]);
        
        DB::table('cars')->insert([
            'user_id'=>'2',
            'car_plate'=>'BCA',
        ]);
        DB::table('cars')->insert([
            'user_id'=>'3',
            'car_plate'=>'ABC',
        ]);
    }
}
