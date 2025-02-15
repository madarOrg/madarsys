<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['key' => 'system_start_date', 'value' => '2025-01-01','branch_id'=>'2'], // استبدل بالتاريخ الفعلي لبداية التشغيل
        ]);
        
        DB::table('settings')->updateOrInsert(
            ['key' => 'inventory_transaction_min_date'],
            ['value' => '2025-01-01'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'fiscal_year_start_date'],
            ['value' => '2025-01-01'],
            ['branch_id'=>'2']
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'fiscal_year_end_date'],
            ['value' => '2024-12-31'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'daily_transaction_limit'],
            ['value' => 100],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'minimum_items_per_transaction'],
            ['value' => 1],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'max_quantity_per_product'],
            ['value' => 1000],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'currency'],
            ['value' => 'RY'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'tax_rate'],
            ['value' => '14'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'is_test_mode'],
            ['value' => 'true'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'max_file_size'],
            ['value' => '10'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'last_settings_update_date'],
            ['value' => now()],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'alert_settings'],
            ['value' => json_encode(['low_stock_alert' => true, 'email_notifications' => true])],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'discount_enabled'],
            ['value' => 'true'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'return_period_days'],
            ['value' => '30'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'logging_enabled'],
            ['value' => 'true'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'auto_close_accounts'],
            ['value' => 'true'],
            ['branch_id'=>'2']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'password_protection_enabled'],
            ['value' => 'true'],
            ['branch_id'=>'2']
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'password_protection_enabled'],
            ['value' => 'true'],
            ['branch_id'=>'2']
        );
    }
}
