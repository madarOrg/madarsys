<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Role;
use Livewire\Component;

class RoleCompanyManager extends Component
{
    public $roles = [];
    public $companies = [];
    public $roleCompanies = [];

    public function mount()
    {
        $this->roles = Role::with('companies')->get();
        $this->companies = Company::all();
    }

    /**
     * إضافة شركة إلى الدور
     */
    public function addCompanyToRole($roleId, $companyId)
    {
        $role = Role::find($roleId);
        if (!$role->companies()->where('company_id', $companyId)->exists()) {
            $role->companies()->attach($companyId);
            $this->updateRoleCompanies($roleId);
            session()->flash('success', 'تمت إضافة الشركة إلى الدور بنجاح.');
        } else {
            session()->flash('error', 'الشركة مضافة مسبقًا لهذا الدور.');
        }
    }

    /**
     * إزالة شركة من الدور
     */
    public function removeCompanyFromRole($roleId, $companyId)
    {
        $role = Role::find($roleId);
        $role->companies()->detach($companyId);
        $this->updateRoleCompanies($roleId);
        session()->flash('success', 'تمت إزالة الشركة من الدور بنجاح.');
    }

    /**
     * تحديث الشركات المرتبطة بدور معين
     */
    public function updateRoleCompanies($roleId)
    {
        $role = Role::find($roleId);
        $this->roles = Role::with('companies')->get();
    }

    public function render()
    {
        return view('livewire.role-company-manager', [
            'roles' => $this->roles,
            'companies' => $this->companies,
        ]);
    }
}
