<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run()
    {
        // إدخال الوحدات الأساسية
        $piece = Unit::create(['name' => 'حبة','branch_id'=> 2]); // الوحدة الأساسية
        $box = Unit::create(['name' => 'كرتون', 'parent_unit_id' => $piece->id, 'conversion_factor' => 10,'branch_id'=> 2]);
        $crate = Unit::create(['name' => 'صندوق', 'parent_unit_id' => $box->id, 'conversion_factor' => 5,'branch_id'=> 2]);

        $kg = Unit::create(['name' => 'كيلوغرام','branch_id'=> 2]); // وحدة أخرى مستقلة
        Unit::create(['name' => 'جرام', 'parent_unit_id' => $kg->id, 'conversion_factor' => 1000,'branch_id'=> 2]);
    }
}
