<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnersTableSeeder extends Seeder
{
    public function run()
    {
        // إضافة 10 موردين
        for ($i = 1; $i <= 10; $i++) {
            DB::table('partners')->insert([
                'name'           => 'مورد ' . $i,
                'type'           => 1, // رقم النوع: 1 للمورد
                'contact_person' => 'مورد مسؤول ' . $i,
                'phone'          => '012345678' . (rand(1000, 9999)),
                'email'          => 'supplier' . $i . '@example.com',
                'address'        => 'عنوان المورد ' . $i,
                'tax_number'     => 'SUP' . rand(1000000, 9999999),
                'is_active'      => true,
                'branch_id'      => 2,
            ]);
        }

        // إضافة 10 عملاء
        for ($i = 1; $i <= 10; $i++) {
            DB::table('partners')->insert([
                'name'           => 'عميل ' . $i,
                'type'           => 2, // رقم النوع: 2 للعميل
                'contact_person' => 'عميل مسؤول ' . $i,
                'phone'          => '011234567' . (rand(1000, 9999)),
                'email'          => 'customer' . $i . '@example.com',
                'address'        => 'عنوان العميل ' . $i,
                'tax_number'     => 'CUS' . rand(1000000, 9999999),
                'is_active'      => true,
                'branch_id'      => 2,
            ]);
        }

        // إضافة 10 مندوبيين
        for ($i = 1; $i <= 10; $i++) {
            DB::table('partners')->insert([
                'name'           => 'مندوب ' . $i,
                'type'           => 3, // رقم النوع: 3 للمندوب
                'contact_person' => 'مندوب مسؤول ' . $i,
                'phone'          => '010987654' . (rand(1000, 9999)),
                'email'          => 'representative' . $i . '@example.com',
                'address'        => 'عنوان المندوب ' . $i,
                'tax_number'     => 'REP' . rand(1000000, 9999999),
                'is_active'      => true,
                'branch_id'      => 2,
            ]);
        }
    }
}
