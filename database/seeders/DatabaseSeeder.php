<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ZoneSeeder;
use Database\Seeders\CarSeeder;
use Database\Seeders\RoleSeeder;
use App\Models\User;
use App\Models\Zone;
use App\Models\Car;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        User::factory(10)->create();
          
         
        // $this->call(ZoneSeeder::class);
        // $this->call(CarSeeder::class);
       
    }
}
