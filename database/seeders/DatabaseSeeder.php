<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            RoleUserSeeder::class,
            CompaniesTableSeeder::class,
            ModulesSeeder::class,
            ModuleActionsSeeder::class,
            PermissionsSeeder::class,
        ]);
    }
}
