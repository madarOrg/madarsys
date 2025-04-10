<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // إنشاء جدول البراندات
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم العلامة التجارية');
            $table->string('code')->unique()->nullable()->comment('رمز مميز للعلامة');
            $table->text('description')->nullable()->comment('وصف العلامة التجارية');
            $table->timestamps();
        });

        // إنشاء جدول بلد الصنع
        Schema::create('manufacturing_countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم البلد المصنع');
            $table->string('code')->unique()->nullable()->comment('رمز البلد مثل SA أو EG');
            $table->text('description')->nullable()->comment('وصف إضافي للبلد');
            $table->timestamps();
        });

        // تعديل جدول المنتجات
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('id')->comment('معرّف البراند');
            $table->unsignedBigInteger('manufacturing_country_id')->nullable()->after('brand_id')->comment('معرّف بلد الصنع');
            $table->text('ingredients')->nullable()->after('description')->comment('المكونات');
            $table->text('notes')->nullable()->after('ingredients')->comment('ملاحظات إضافية');

            // علاقات المفاتيح الخارجية
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('manufacturing_country_id')->references('id')->on('manufacturing_countries')->onDelete('set null');
        });

        // ✅ إدخال بيانات افتراضية للبراندات
        DB::table('brands')->insert([
            ['name' => 'Brand A', 'code' => 'BR-A', 'description' => 'العلامة التجارية A', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brand B', 'code' => 'BR-B', 'description' => 'العلامة التجارية B', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brand C', 'code' => 'BR-C', 'description' => 'العلامة التجارية C', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ✅ إدخال بيانات افتراضية لبلد الصنع
        DB::table('manufacturing_countries')->insert([
            ['name' => 'السعودية', 'code' => 'SA', 'description' => 'منتج سعودي', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مصر', 'code' => 'EG', 'description' => 'منتج مصري', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الصين', 'code' => 'CN', 'description' => 'منتج صيني', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ✅ تحديث المنتجات الحالية بقيم افتراضية
        $brand = DB::table('brands')->inRandomOrder()->first();
        $country = DB::table('manufacturing_countries')->inRandomOrder()->first();

        DB::table('products')->update([
            'brand_id' => $brand?->id,
            'manufacturing_country_id' => $country?->id,
            'ingredients' => 'مكونات افتراضية: مكون 1، مكون 2، مكون 3',
            'notes' => 'تم التحديث تلقائيًا'
        ]);
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['manufacturing_country_id']);
            $table->dropColumn(['brand_id', 'manufacturing_country_id', 'ingredients', 'notes']);
        });

        Schema::dropIfExists('brands');
        Schema::dropIfExists('manufacturing_countries');
    }
};
