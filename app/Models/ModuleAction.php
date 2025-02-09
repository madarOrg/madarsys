<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class ModuleAction extends Model {
    use HasBranch,HasFactory;
    
    protected $fillable = ['module_id', 'name', 'action_key','icon', 'route','branch_id'];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function module() {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function permissions() {
        return $this->hasMany(Permission::class, 'module_action_id');
    }
}
