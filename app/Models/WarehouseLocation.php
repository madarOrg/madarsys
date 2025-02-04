<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseLocation extends Model
{
    use HasFactory;

    // تعريف الجدول إذا كان مختلف عن الاسم الافتراضي
    protected $table = 'warehouse_locations';

    // تحديد الأعمدة القابلة للتحديث
    protected $fillable = [
        'warehouse_id',
        'location_name',
        'aisle',
        'shelf_number',
        'product_type',
        'capacity',
        'current_occupancy',
        'zone_id',
    ];

    /**
     * العلاقة مع موديل Warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * العلاقة مع موديل Zone.
     */
    // public function zone()
    // {
    //     return $this->belongsTo(Zone::class);
    // }

    /**
     * إرجاع الحالة إذا كانت المنطقة مشغولة.
     */
    public function isOccupied(): bool
    {
        return $this->current_occupancy >= $this->capacity;
    }
}
