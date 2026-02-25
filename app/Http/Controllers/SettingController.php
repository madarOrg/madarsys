<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    // عرض صفحة الإعدادات
    public function index()
    {
        try {
            $settings = Setting::all();
            return view('settings.index', compact('settings'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage()], 500);
        }
    }

    // تحديث الإعدادات
    public function update(Request $request)
    {
        try {
            $data = $request->except('_token', '_method'); // نستثني التوكن وطريقة الإرسال
    
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
    
            return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات: ' . $e->getMessage());
        }
    }
}