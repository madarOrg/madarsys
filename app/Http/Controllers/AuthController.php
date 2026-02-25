<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        try {
            return view('auth.login');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'حدث خطأ أثناء تحميل صفحة تسجيل الدخول: ' . $e->getMessage());
        }
    }

    /**
     * معالجة تسجيل الدخول
     */public function login(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // تحقق أولاً من وجود المستخدم في قاعدة البيانات
        $user = User::where('email', $request->email)->first();

        // إذا كان المستخدم غير موجود أو حسابه معلق (status = 0)
        if (!$user || $user->status == 0) {
            return back()->withErrors([
                'email' => 'حسابك معلق أو غير مفعل، لا يمكنك تسجيل الدخول في الوقت الحالي.',
            ]);
        }

        // محاولة تسجيل الدخول باستخدام بيانات الاعتماد المقدمة
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->intended('/dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
        }

        // في حالة فشل التحقق من بيانات الاعتماد
        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);
    } catch (Exception $e) {
        // التعامل مع الاستثناءات
        return back()->with('error', 'حدث خطأ أثناء محاولة تسجيل الدخول: ' . $e->getMessage());
    }
}


    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'حدث خطأ أثناء تسجيل الخروج: ' . $e->getMessage());
        }
    }
}
