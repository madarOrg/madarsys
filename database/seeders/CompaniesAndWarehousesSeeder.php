<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\User; // إذا كنت تريد إضافة مشرف للمستودعات

class CompaniesAndWarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إنشاء شركة واحدة
        $company = Company::create([
            'name' => 'شركة المدارات',
            'logo' => 'path_to_logo', // قم بوضع رابط أو مسار للشعار
            'phone_number' => '123456997890',
            'email' => 'company@example.com',
            'address' => 'عنوان الشركة',
            'additional_info' => 'معلومات إضافية',
            'settings' => json_encode(['key' => 'value']), // إعدادات JSON
        ]);

        // إنشاء 5 فروع لهذه الشركة
        for ($i = 1; $i <= 5; $i++) {
            $branch = Branch::create([
                'name' => 'فرع ' . $i,
                'address' => 'عنوان الفرع ' . $i,
                'contact_info' => 'معلومات الاتصال للفرع ' . $i,
                'company_id' => $company->id,
            ]);

            // إنشاء 3 مستودعات لكل فرع
            for ($j = 1; $j <= 3; $j++) {
                Warehouse::create([
                    'name' => 'مستودع ' . $j . ' في الفرع ' . $i,
                    'code' => 'WH' . $i . $j, // كود المستودع
                    'address' => 'عنوان المستودع ' . $j,
                    'contact_info' => 'معلومات الاتصال للمستودع ' . $j,
                    'branch_id' => $branch->id,
                    // 'supervisor_id' => User::inRandomOrder()->first()->id, // يمكنك اختيار مشرف عشوائي
                    'latitude' => 30.000 + $i, // مثال لخط العرض
                    'longitude' => 31.000 + $j, // مثال لطول العرض
                    'area' => 1000.5, // مساحة المستودع
                    'capacity' => 5000, // سعة التخزين
                    'is_smart' => true, // هل هو مستودع ذكي
                    'has_security_system' => true, // هل يوجد نظام أمني
                    'has_cctv' => true, // هل يوجد كاميرات مراقبة
                    'is_integrated_with_wms' => false, // هل هو مدمج مع WMS
                    'last_maintenance' => now(), // تاريخ آخر صيانة
                    'has_automated_systems' => true, // هل يحتوي على أنظمة آلية
                    'temperature' => 22.5, // درجة الحرارة
                    'humidity' => 60, // الرطوبة
                ]);
            }
        }
    }
}
