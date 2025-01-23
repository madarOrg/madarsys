<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
