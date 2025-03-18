<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\{
    HasBranch,
    HasUser
};

class WarehouseLocation extends Model
{
    use HasUser, HasBranch, HasFactory;

    // تعريف الجدول إذا كان مختلف عن الاسم الافتراضي
    protected $table = 'warehouse_locations';

    protected $fillable = [
        'branch_id',
        'warehouse_id',      // معرف المستودع
        'storage_area_id',   // معرف منطقة التخزين
        'aisle',             // رقم الممر
        'rack',              // رقم الرف
        'rack_code',  // إضافة كود الرف
        'shelf',             // رقم الرف الفرعي
        'position',          // الموقع على الرف
        'barcode',           // باركود الموقع (يجب أن يكون فريدًا)
        'is_occupied',       // حالة الموقع (مشغول أو فارغ)
        'notes',             // ملاحظات إضافية
        'created_user',
        'updated_user'
    ];

    /**
     * العلاقة مع موديل Warehouse
     */
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($location) {
            $location->rack_code = self::generateRackCode($location);
        });
    }
    public static function generateRackCode($location)
    {
        // أول 3 أحرف من اسم المستودع
        $warehouseCode = strtoupper(substr($location->warehouse->code ?? 'GEN', 0, 3));
    
        $storageAreaCode = str_pad($location->storageArea->id ?? '000', 3, '0', STR_PAD_LEFT);
    
        // توليد الرقم التسلسلي باستخدام الرقم الأكبر من `rack_code` الموجود مع إضافة أصفار لجعل الطول ثابتًا
        $lastRackCode = self::where('warehouse_id', $location->warehouse_id)
                            ->where('storage_area_id', $location->storage_area_id)
                            ->max('rack_code');
    
        $serialNumber = $lastRackCode ? (intval(substr($lastRackCode, -4)) + 1) : 1; // استخراج الرقم الأخير وإضافة 1 له
        $serialNumber = str_pad($serialNumber, 4, '0', STR_PAD_LEFT); // إضافة أصفار لجعل الرقم مكونًا من 4 أرقام
    // dd("{$warehouseCode}-{$storageAreaCode}-{$serialNumber}");
        // دمج الأحرف مع الرقم التسلسلي
        return "{$warehouseCode}-{$storageAreaCode}-{$serialNumber}";
    }
        
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
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
    public function getFullLocationAttribute()
    {
        return "{$this->aisle}-{$this->rack}-{$this->shelf}-{$this->position}";
    }
}
