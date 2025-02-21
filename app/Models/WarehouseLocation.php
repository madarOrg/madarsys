<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasUser
};

class WarehouseLocation extends Model
{
    use HasUser,HasFactory;

    // تعريف الجدول إذا كان مختلف عن الاسم الافتراضي
    protected $table = 'warehouse_locations';

    protected $fillable = [
        'warehouse_id',      // معرف المستودع
        'storage_area_id',   // معرف منطقة التخزين
        'aisle',             // رقم الممر
        'rack',              // رقم الرف
        'shelf',             // رقم الرف الفرعي
        'position',          // الموقع على الرف
        'barcode',           // باركود الموقع (يجب أن يكون فريدًا)
        'is_occupied',       // حالة الموقع (مشغول أو فارغ)
        'notes',             // ملاحظات إضافية
        'created_user', 'updated_user'];

    /**
     * العلاقة مع موديل Warehouse
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * العلاقة مع موديل Zone
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

     /**
     * العلاقة مع منطقة التخزين
     */
    public function storageArea()
    {
        return $this->belongsTo(WarehouseStorageArea::class, 'storage_area_id');
    }

    /**
     * إرجاع الحالة إذا كانت المنطقة مشغولة.
     */
    public function isOccupied(): bool
    {
        return $this->current_occupancy >= $this->capacity;
    }
}
