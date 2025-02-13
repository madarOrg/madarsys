<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    // عرض صفحة الإعدادات
    public function index()
    {
        $settings = Setting::all();
        return view('settings.index', compact('settings'));
    }

    // تحديث الإعدادات
    public function update(Request $request)
    {
        $request->validate([
            'inventory_transaction_min_date' => 'required|date|before_or_equal:today',
        ]);

        Setting::where('key', 'inventory_transaction_min_date')
            ->update(['value' => $request->inventory_transaction_min_date]);

        return redirect()->back()->with('success', 'تم تحديث السياسات بنجاح');
    }
}
