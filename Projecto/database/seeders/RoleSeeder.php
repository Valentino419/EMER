<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'inspector', 'user'];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }
    }
}
