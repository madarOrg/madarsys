<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model {
    
    use HasFactory;
    protected $fillable = ['name', 'key', 'scope_level'];

    // الإجراءات (Actions) المرتبطة بهذه الوحدة
    public function actions() {
        return $this->hasMany(ModuleAction::class, 'module_id');
    }
    
   
}
