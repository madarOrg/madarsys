<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerTypeSeeder extends Seeder
{
    /**
     * تشغيل Seeder لإضافة بيانات مبدئية لأنواع الشركاء.
     */
    public function run(): void
    {
        DB::table('partner_types')->insert([
            ['name' => 'مورد', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],            // supplier
            ['name' => 'عميل', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],            // customer
            ['name' => 'مندوب', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],           // representative
            ['name' => 'جهة متبرعة', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],       // donor
            ['name' => 'شريك لوجستي', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],     // logistics_partner
            ['name' => 'ورشة تدوير', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],      // recycling_workshop
            ['name' => 'جهة حكومية', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],      // government_agency
            ['name' => 'مؤسسة غير ربحية', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()], // non_profit
        ]);
    }
}
