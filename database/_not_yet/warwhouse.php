<?php

Schema::create('warehouses', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->string('address')->nullable();
    $table->text('contact_info')->nullable();
    $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
    $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
    
    // الموقع الجغرافي
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
    
    // السعة التخزينية
    $table->float('area', 8, 2)->nullable();
    $table->float('capacity')->nullable();
    
    // نوع المستودع وحالته
    $table->enum('type', ['general', 'cold_storage', 'freezer', 'chemical', 'electronics', 'hazardous'])->default('general');
    $table->enum('status', ['active', 'inactive', 'under_maintenance', 'full'])->default('active');
    
    // القيود الفيزيائية
    $table->float('max_weight_capacity')->nullable();
    $table->float('max_volume_capacity')->nullable();
    
    // أنظمة الأمان والتكنولوجيا
    $table->boolean('is_smart')->default(false);
    $table->boolean('has_security_system')->default(false);
    $table->boolean('has_cctv')->default(false);
    $table->boolean('has_automated_systems')->default(false);
    $table->boolean('is_integrated_with_wms')->default(false);
    
    // أنظمة الحماية
    $table->string('fire_safety_rating')->nullable();
    
    // ظروف التخزين
    $table->float('temperature')->nullable();
    $table->float('humidity')->nullable();
    
    // مصدر الطاقة
    $table->enum('energy_source', ['electricity', 'solar', 'generator'])->default('electricity');
    
    // ساعات العمل
    $table->json('operating_hours')->nullable();
    
    // قيود الوصول
    $table->boolean('requires_access_card')->default(false);
    $table->boolean('requires_security_clearance')->default(false);
    
    // آخر صيانة
    $table->timestamp('last_maintenance')->nullable();
    
    // توقيتات الإنشاء والتحديث
    $table->timestamps();
});
//////////////
/////add categories fields