<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

use function Laravel\Prompts\password;

class PasswordController extends Controller
{
    // عرض صفحة تغيير كلمة المرور
    public function create()
    {
        return view('auth.reset-password'); // تأكد من وجود هذا الملف في views/auth
    }

    // معالجة طلب تغيير كلمة المرور
    public function store(Request $request)
    {
       // التحقق من البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
           
            'password' => 'required|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
       // تحديث كلمة المرور
        $user = User::where('email', $request->email)->first();
         

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route(' auth.login')->with( 'تم تحديث كلمة المرور بنجاح. يرجى تسجيل الدخول.');

        }
       
        return back()->with("خطأ، لم يتم العثور على المستخدم");
    }
}
