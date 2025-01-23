<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
  
        public function run()
        {
            $roles = [
                ['name' => 'مشرف', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'مندوب', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'أمين المخزن', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'محاسب', 'created_at' => now(), 'updated_at' => now()],
            ];
    
            DB::table('roles')->insert($roles);
        }
    }
    

