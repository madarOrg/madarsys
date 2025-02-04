<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseStorageArea extends Model
{
    use HasFactory;

    // تحديد الجدول في قاعدة البيانات
    protected $table = 'warehouse_storage_areas';

    // تحديد الأعمدة التي يمكن ملؤها بشكل جماعي
    protected $fillable = [
        'warehouse_id',
        'area_name',
        'area_type',
        'capacity',
        'current_occupancy',
        'zone_id',
        'storage_conditions',
    ];

    // تحديد العلاقة مع المستودعات
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // // تحديد العلاقة مع المناطق الفرعية (اختياري)
    // public function zone()
    // {
    //     return $this->belongsTo(Zone::class);
    // }
}
