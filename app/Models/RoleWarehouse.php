<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,    
HasUser
};

class RoleWarehouse extends Model
{
    use HasUser,HasBranch,HasFactory;

    protected $table = 'role_warehouse'; 
    protected $fillable = ['role_id', 'warehouse_id', 'branch_id','created_user', 'updated_user'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
