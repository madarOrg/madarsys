<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'المبيعات', 'description' => 'قسم المبيعات المسؤول عن علاقات العملاء وبيع المنتجات.', 'is_active' => true],
            ['name' => 'الموارد البشرية', 'description' => 'قسم الموارد البشرية المسؤول عن علاقات الموظفين والتوظيف.', 'is_active' => true],
            ['name' => 'تكنولوجيا المعلومات', 'description' => 'قسم تكنولوجيا المعلومات المسؤول عن إدارة البنية التحتية التقنية للشركة.', 'is_active' => true],
            ['name' => 'المالية', 'description' => 'قسم المالية المسؤول عن إدارة السجلات المالية وإعداد الميزانية.', 'is_active' => true],
            ['name' => 'اللوجستيات', 'description' => 'قسم اللوجستيات المسؤول عن الإشراف على نقل وتخزين البضائع.', 'is_active' => true],
            ['name' => 'دعم العملاء', 'description' => 'قسم دعم العملاء المسؤول عن التعامل مع استفسارات وشكاوى العملاء.', 'is_active' => true],
            ['name' => 'فسم الصيانة', 'description' => 'قسم صيانة المرتجعات.', 'is_active' => true,'type'=>'2'],
            ['name' => 'قسم الانتاج ', 'description' => 'قسم خاص بالانتاج', 'is_active' => true,'type'=>'2'],
        ]);
    }
}
