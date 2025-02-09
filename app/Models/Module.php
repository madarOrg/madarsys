<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class Module extends Model {
    
    use HasBranch,HasFactory;
    protected $fillable = ['name', 'key', 'scope_level','branch_id'];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    // الإجراءات (Actions) المرتبطة بهذه الوحدة
    public function actions() {
        return $this->hasMany(ModuleAction::class, 'module_id');
    }
    
   
}
