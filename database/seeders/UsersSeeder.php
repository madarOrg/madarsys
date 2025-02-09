<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id'      => 2,

            ],
            [
                'name' => 'Representative User',
                'email' => 'representative@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id'      => 2,

            ],
            [
                'name' => 'Storekeeper User',
                'email' => 'storekeeper@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id'      => 2,

            ],
            [
                'name' => 'Accountant User',
                'email' => 'accountant@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
                'branch_id'      => 2,

            ],
        ];

        DB::table('users')->insert($users);
    }
}
