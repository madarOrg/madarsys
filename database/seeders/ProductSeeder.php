<?php

namespace Database\Seeders;
use App\Models\Partner;
use App\Models\Category;
use App\Models\Product;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
class ProductSeeder extends Seeder
{
    public function run()
    {
        // إنشاء مثيل من Faker
        $faker = Faker::create();
        $categoryIds = Category::pluck('id')->toArray();
        $supplierIds = Partner::pluck('id')->toArray(); // احصل على جميع الموردين المتاحين

        // إدخال 50 منتجًا
        for ($i = 0; $i < 50; $i++) {
            // إنشاء منتج جديد
            Product::create([
                'name' => $faker->word . ' ' . $faker->word, // اسم عشوائي
                'image' => 'products/image' . rand(1, 15) . '.jpg', // تحديد صورة عشوائية من الصور الجاهزة
                'description' => $faker->sentence, // وصف عشوائي
                'category_id' => $faker->randomElement($categoryIds) ?? null, // تأكد من أن التصنيف موجود
                'supplier_id' => count($supplierIds) > 0 ? $faker->randomElement($supplierIds) : null, // تأكد من أن المورد موجود
                'barcode' => $faker->unique()->isbn13, // باركود عشوائي
                'sku' => $faker->unique()->numerify('SKU-#####'), // SKU عشوائي
                'purchase_price' => $faker->randomFloat(2, 5, 100), // سعر شراء عشوائي
                'selling_price' => $faker->randomFloat(2, 10, 150), // سعر بيع عشوائي
                'stock_quantity' => rand(10, 100), // كمية عشوائية في المخزون
                'min_stock_level' => rand(1, 10), // الحد الأدنى للمخزون
                'max_stock_level' => rand(100, 200), // الحد الأقصى للمخزون
                'unit' => 'pcs', // وحدة القياس
                'is_active' => true, // حالة التفعيل
                'branch_id'      => 2,
            ]);
        }
    }
}
