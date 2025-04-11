<?php

namespace App\Http\Controllers;

use App\Models\ManufacturingCountry;
use Illuminate\Http\Request;

class ManufacturingCountryController extends Controller
{
    // عرض قائمة الشركات المصنعة
    public function index()
    {
        $manufacturingCountries = ManufacturingCountry::all();
        return view('manufacturing_countries.index', compact('manufacturingCountries'));
    }

    // عرض النموذج لإنشاء شركة جديدة
    public function create()
    {
        return view('manufacturing_countries.create');
    }

    // تخزين شركة جديدة في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:500',
        ]);

        ManufacturingCountry::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('manufacturing_countries.index')->with('success', 'تم إضافة الشركة المصنعة بنجاح.');
    }

    // عرض نموذج التعديل
    public function edit($id)
    {
        $manufacturer = ManufacturingCountry::findOrFail($id);
        return view('manufacturing_countries.edit', compact('manufacturer'));
    }

    // تحديث بيانات الشركة المصنعة
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:500',
        ]);

        $manufacturer = ManufacturingCountry::findOrFail($id);
        $manufacturer->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('manufacturing_countries.index')->with('success', 'تم تحديث بيانات الشركة المصنعة بنجاح.');
    }

    // حذف شركة من قاعدة البيانات
    public function destroy($id)
    {
        $manufacturer = ManufacturingCountry::findOrFail($id);
        $manufacturer->delete();

        return redirect()->route('manufacturing_countries.index')->with('success', 'تم حذف الشركة المصنعة بنجاح.');
    }
}
