<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ModulesSeeder extends Seeder
{
    public function run()
    {
        DB::table('modules')->insert([
            [
                'id' => 4,
                'name' => 'إدارة الشحنات',
                'key' => $this->generateKey('إدارة الشحنات'),
                'scope_level' => 'branch',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 6,
                'name' => 'إدارة الشركاء',
                'key' => $this->generateKey('إدارة الشركاء'),
                'scope_level' => 'company',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 2,
                'name' => 'إدارة الشركات و المستودعات',
                'key' => $this->generateKey('إدارة الشركات و المستودعات'),
                'scope_level' => 'branch',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 11,
                'name' => 'إدارة العملاء والمحلات',
                'key' => $this->generateKey('إدارة العملاء والمحلات'),
                'scope_level' => 'branch',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 7,
                'name' => 'إدارة العوائد (المرتجعات)',
                'key' => $this->generateKey('إدارة العوائد (المرتجعات)'),
                'scope_level' => 'company',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 5,
                'name' => 'إدارة الفواتير',
                'key' => $this->generateKey('إدارة الفواتير'),
                'scope_level' => 'company',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 3,
                'name' => 'إدارة المخزون',
                'key' => $this->generateKey('إدارة المخزون'),
                'scope_level' => 'warehouse',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 1,
                'name' => 'إدارة المستخدمين',
                'key' => $this->generateKey('إدارة المستخدمين'),
                'scope_level' => 'company',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 8,
                'name' => 'إدارة الموردين',
                'key' => $this->generateKey('إدارة الموردين'),
                'scope_level' => 'company',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 12,
                'name' => 'إدارة مندوبين المحلات',
                'key' => $this->generateKey('إدارة مندوبين المحلات'),
                'scope_level' => 'branch',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 10,
                'name' => 'إنشاء التقارير',
                'key' => $this->generateKey('إنشاء التقارير'),
                'scope_level' => 'company',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ],
            [
                'id' => 9,
                'name' => 'تتبع الكميات',
                'key' => $this->generateKey('تتبع الكميات'),
                'scope_level' => 'warehouse',
                'created_at' => Carbon::parse('2025-01-31 16:26:04'),
                'updated_at' => Carbon::parse('2025-01-31 16:26:04'),
                'branch_id' => 2,
            ]
        ]);
    }

    private function generateKey($text)
    {
        $key = str_replace([' ', 'إدارة', 'ال'], '', $text);
        return Str::snake($key);
    }
}
