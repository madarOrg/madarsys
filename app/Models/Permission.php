<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Permission extends Model
{
    use HasFactory;
    protected $fillable = ['name','module_id', 'module_action_id', 'permission_key', 'scope_level'];

    public function module() {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function moduleAction() {
        return $this->belongsTo(ModuleAction::class, 'module_action_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')->withTimestamps();
    }
    
    

   

    
}
