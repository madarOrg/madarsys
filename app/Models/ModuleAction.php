<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAction extends Model {
    use HasFactory;
    
    protected $fillable = ['module_id', 'name', 'action_key','icon', 'route'];


    public function module() {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function permissions() {
        return $this->hasMany(Permission::class, 'module_action_id');
    }
}
