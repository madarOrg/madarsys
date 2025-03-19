<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CompaniesAndWarehousesSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
            RoleUserSeeder::class,
            ModulesSeeder::class,
            ModuleActionsSeeder::class,
            PermissionsSeeder::class,
            RolePermissionsSeeder::class,
            SettingsSeeder::class,
            UnitSeeder::class,
            PartnerTypeSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PaymentTypesSeeder::class,
            PartnerSeeder::class,
            CurrencySeeder::class,
        ]);
    }
}
