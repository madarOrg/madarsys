<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class PartnerType extends Model
{
    use HasBranch,HasFactory;

    protected $table = 'partner_types'; // تحديد اسم الجدول

    protected $fillable = ['name','branch_id']; // السماح بالتعبئة الجماعية
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    /**
     * العلاقة بين نوع الشريك والشركاء (كل نوع له عدة شركاء).
     */
    public function partners()
    {
        return $this->hasMany(Partner::class, 'type', 'name');
    }
}
