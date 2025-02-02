<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{ // دالة لعرض نموذج التسجيل
    public function create()
    {
        // جلب قائمة الأدوار المتاحة من جدول الأدوار
        $roles = Role::all();
        return view('auth.signup', compact('roles'));
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

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // تعيين الدور للمستخدم
        $role = \App\Models\Role::where('name', $request->role)->first(); // استرجاع الدور بناءً على الاسم
        $user->roles()->attach($role->id); // ربط المستخدم بالدور

        return redirect()->route('login')->with("تم إنشاء الحساب بنجاح");
    }
}
