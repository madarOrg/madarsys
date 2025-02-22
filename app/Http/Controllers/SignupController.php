<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{
    // دالة لعرض نموذج التسجيل
    public function create()
    {
        try {
            // جلب قائمة الأدوار المتاحة من جدول الأدوار
            $roles = Role::all();
            return view('auth.signup', compact('roles'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب الأدوار: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
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
            $role = Role::where('name', $request->role)->first(); // استرجاع الدور بناءً على الاسم
            $user->roles()->attach($role->id); // ربط المستخدم بالدور

            return redirect()->route('login')->with('success', 'تم إنشاء الحساب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage());
        }
    }
}
