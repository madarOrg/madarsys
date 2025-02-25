<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    public function run()
    {
        $roleUser = [
            // Assuming the user IDs and role IDs are 1-based and sequential
            ['user_id' => 1, 'role_id' => 1], // Admin User -> Supervisor
            ['user_id' => 2, 'role_id' => 2], // Representative User -> Representative
            ['user_id' => 3, 'role_id' => 3], // Storekeeper User -> Storekeeper
            ['user_id' => 4, 'role_id' => 4], // Accountant User -> Accountant
        ];

        DB::table('role_user')->insert($roleUser);
    }
}
