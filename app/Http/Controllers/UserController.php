<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search'); // الحصول على نص البحث

    $users = User::when($search, function ($query, $search) {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
    })->paginate(7); // جلب 7 مستخدمين في كل صفحة

    return view('users.index', compact('users'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // جلب قائمة الأدوار المتاحة من جدول الأدوار
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    { 
        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|exists:roles,name', // التحقق من أن الدور موجود في جدول roles
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $branchId = $request->branch_id ?? auth()->user()->branch_id ?? null;

        // إنشاء المستخدم مع branch_id
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch_id' => $branchId, // يتم تعيين البرانش إذا كان متاحًا
        ]);
        
        // تعيين الدور للمستخدم
        $role = \App\Models\Role::where('name', $request->role)->first(); // استرجاع الدور بناءً على الاسم
        $user->roles()->attach($role->id); // ربط المستخدم بالدور

        return redirect()->route('users.create')->with('success', 'تم إنشاء الحساب بنجاح');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // جلب جميع الأدوار المتاحة من جدول الأدوار
        $roles = Role::all();
        
        // تمرير المستخدم والأدوار إلى الـ view
        return view('users.edit', compact('user', 'roles'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // قواعد التحقق الأساسية
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:0,1', // (1=فعال، 0=متوقف)
        ];
    
        // تحقق من كلمة المرور فقط إذا كانت محدثة
        if ($request->has('password') && $request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }
    
        // التحقق من البيانات
        $validated = $request->validate($rules);
        // إذا كانت كلمة المرور محدثة، قم بتعيين كلمة المرور الجديدة
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']); // إزالة كلمة المرور إذا لم يتم تحديثها
        }
    
        // تحديث بيانات المستخدم
        $user->update($validated);
        if ($request->has('role')) {
            $role = Role::where('name', $request->role)->first();
        
            // تحقق إذا كان للمستخدم دور بالفعل
            if ($user->roles()->where('role_id', $role->id)->exists()) {
                // إذا كان الدور موجودًا، لا حاجة لتحديثه
                return redirect()->route('users.index')->with( 'تم تحديث المستخدم بنجاح');
            } else {
                // إذا لم يكن الدور موجودًا، قم بتحديثه
                $user->roles()->sync([$role->id]); // أو يمكنك استخدام attach في حالة عدم الرغبة في الحذف
            }
        }
        
    
        return redirect()->route('users.index')->with( 'تم تحديث المستخدم بنجاح');
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with( 'تم حذف المستخدم بنجاح');
    }
}
