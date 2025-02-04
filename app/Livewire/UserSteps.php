<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class UserSteps extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $roles = [];
    public $currentStep = 1;
    public $user;
    public $selectedRole; // لتخزين الدور المحدد
    public $userRoles = []; // لتخزين أدوار المستخدم

    /**
     * إنشاء المستخدم في الخطوة الأولى.
     */
    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // إنشاء المستخدم وتخزينه في المتغير $this->user
        $this->user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        // تحديث الأدوار المرتبطة بالمستخدم
        $this->updateUserRoles();
        
        // الانتقال إلى الخطوة التالية
        $this->currentStep = 2;
    }

    /**
     * تعيين الأدوار للمستخدم.
     */
    public function assignRoles()
    {
        
        $this->validate([
            'selectedRole' => 'required|exists:roles,id',
        ]);

        // التأكد من أن المستخدم تم إنشاؤه
        if (!$this->user) {
            session()->flash('error', 'يجب إنشاء مستخدم قبل تعيين الأدوار.');
            return;
        }

        // التأكد من أن الدور غير مضاف مسبقًا
        if ($this->user->roles()->where('role_id', $this->selectedRole)->exists()) {
            session()->flash('error', 'الدور مضاف مسبقًا.');
            return;
        }

        // إضافة الدور بدون تكرار
        $this->user->roles()->syncWithoutDetaching($this->selectedRole);

        // تحديث الأدوار بعد الإضافة
        $this->updateUserRoles();

        // عرض رسالة النجاح
        session()->flash('success', 'تمت إضافة الدور بنجاح.');
    }

    /**
     * تحديث قائمة الأدوار المرتبطة بالمستخدم.
     */
    public function updateUserRoles()
    {
        if ($this->user) {
            // التحقق من الأدوار المرتبطة
            $this->userRoles = $this->user->roles()->get(['id', 'name'])->toArray();
            
        }
    }
    
    /**
     * إزالة دور معين من المستخدم.
     */
    public function removeRole($roleId)
    {
        if ($this->user) {
            // إزالة الدور
            $this->user->roles()->detach($roleId);
            
            // تحديث الأدوار بعد الإزالة
            $this->updateUserRoles();

            // عرض رسالة النجاح
            session()->flash('success', 'تمت إزالة الدور بنجاح.');
        }
    }

    /**
     * التنقل بين الخطوات.
     */
    public function nextStep()
    {
        $this->goToStep($this->currentStep + 1);
    }

    public function goToStep($step)
    {
        if ($step > $this->currentStep) {
            $this->validateStep($this->currentStep);
        }
        $this->currentStep = $step;
    }

    /**
     * التحقق من صحة المدخلات بناءً على الخطوة الحالية.
     */
    private function validateStep($step)
    {
        if ($step == 1) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);
        } elseif ($step == 2) {
            $this->validate([
                'selectedRole' => 'required|exists:roles,id',
            ]);
        }
    }

    /**
     * تحميل البيانات عند التهيئة.
     */
    public function mount()
    {
        // تحميل جميع الأدوار
        $this->roles = Role::all();
    }

    /**
     * عرض المكون.
     */
    public function render()
    {
        return view('livewire.user-steps', [
            'roles' => $this->roles,
            'userRoles' => $this->userRoles,
        ]);
    }
}
