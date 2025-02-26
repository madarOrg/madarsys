<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $warehouses = \App\Models\Warehouse::all();

        foreach ($warehouses as $warehouse) {
            if ($warehouse->name == 'مستودع 1 في الفرع1') {
                // تقسيم حسب طبيعة المواد المخزنة
                    

               
               
                $warehouse->zones()->create([
                    'name' => 'منطقة المواد القابلة لإعادة التدوير',
                    'code' => 'RECYCLABLE-' . $warehouse->id,
                    'capacity' => 800,
                    'current_occupancy' => 400,
                    'description' => 'تخزين المواد التي يمكن إعادة استخدامها أو إعادة تدويرها',
                ]);
            }

            if ($warehouse->name == 'مستودع 2 في الفرع1') {
                // تقسيم حسب درجة الحرارة ومتطلبات التخزين
               


            if ($warehouse->name == 'مستودع 3 في الفرع1') {
                // تقسيم حسب طرق التخزين
                $warehouse->zones()->create([
                    'name' => 'منطقة الرفوف العالية',
                    'code' => 'RACK-' . $warehouse->id,
                    'capacity' => 1200,
                    'current_occupancy' => 800,
                    'description' => 'تخزين البضائع على رفوف طويلة باستخدام الرافعات الشوكية',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة التخزين الأرضي',
                    'code' => 'FLOOR-' . $warehouse->id,
                    'capacity' => 1000,
                    'current_occupancy' => 600,
                    'description' => 'تخزين البضائع مباشرة على الأرض',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة التخزين في الحاويات',
                    'code' => 'CONTAINER-' . $warehouse->id,
                    'capacity' => 500,
                    'current_occupancy' => 300,
                    'description' => 'تخزين المنتجات داخل حاويات مغلقة',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة البضائع السائبة',
                    'code' => 'BULK-' . $warehouse->id,
                    'capacity' => 1500,
                    'current_occupancy' => 1000,
                    'description' => 'تخزين المنتجات بكميات كبيرة دون تقسيمها إلى وحدات صغيرة',
                ]);
            }

            if ($warehouse->name == 'مستودع 1 في الفرع2') {
                // تقسيم حسب عمليات التشغيل
                $warehouse->zones()->create([
                    'name' => 'منطقة الاستلام',
                    'code' => 'RECEIVE-' . $warehouse->id,
                    'capacity' => 600,
                    'current_occupancy' => 300,
                    'description' => 'مخصصة لاستقبال وفحص البضائع الواردة قبل تخزينها',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة التجهيز والتعبئة',
                    'code' => 'PACKING-' . $warehouse->id,
                    'capacity' => 700,
                    'current_occupancy' => 500,
                    'description' => 'تحضير المنتجات للشحن أو النقل',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة الشحن',
                    'code' => 'SHIPPING-' . $warehouse->id,
                    'capacity' => 900,
                    'current_occupancy' => 600,
                    'description' => 'مكان تجهيز البضائع وتحميلها إلى وسائل النقل',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة الإرجاع',
                    'code' => 'RETURNS-' . $warehouse->id,
                    'capacity' => 500,
                    'current_occupancy' => 200,
                    'description' => 'تخزين المنتجات المعادة قبل فحصها أو إعادة توزيعها',
                ]);
            }

            if ($warehouse->name == 'مستودع 2 في الفرع2') {
                // تقسيم حسب الأولوية وسرعة التداول
                $warehouse->zones()->create([
                    'name' => 'منطقة التخزين السريع',
                    'code' => 'FAST-' . $warehouse->id,
                    'capacity' => 1000,
                    'current_occupancy' => 900,
                    'description' => 'مخصصة للمنتجات التي يتم تداولها بشكل متكرر لسهولة الوصول إليها',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة التخزين البطيء',
                    'code' => 'SLOW-' . $warehouse->id,
                    'capacity' => 500,
                    'current_occupancy' => 200,
                    'description' => 'مخصصة للمنتجات ذات معدل دوران منخفض',
                ]);

                $warehouse->zones()->create([
                    'name' => 'منطقة التخزين المؤقت',
                    'code' => 'TEMPORARY-' . $warehouse->id,
                    'capacity' => 400,
                    'current_occupancy' => 150,
                    'description' => 'تخزين المنتجات لفترة قصيرة قبل نقلها إلى مكان آخر',
                ]);
            }
        }
    }
}
