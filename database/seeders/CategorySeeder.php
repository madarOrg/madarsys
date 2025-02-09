<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * تشغيل Seeder لملء جدول الفئات ببعض البيانات الافتراضية.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name'        => 'إلكترونيات',
                'code'        => 'ELEC',
                'description' => 'الأجهزة الإلكترونية مثل الهواتف الذكية والحواسيب.',
                'branch_id'   => 2, // تعيين الفرع إلى 2
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'ملابس',
                'code'        => 'CLOTH',
                'description' => 'ملابس رجالية ونسائية وأطفال.',
                'branch_id'   => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'أثاث',
                'code'        => 'FURN',
                'description' => 'الأثاث المنزلي والمكتبي.',
                'branch_id'   => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'مواد غذائية',
                'code'        => 'FOOD',
                'description' => 'المواد الغذائية والمنتجات الاستهلاكية.',
                'branch_id'   => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'معدات صناعية',
                'code'        => 'INDUST',
                'description' => 'المعدات الثقيلة والمستلزمات الصناعية.',
                'branch_id'   => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
