<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

class ZonesController extends Controller
{
    // عرض جميع المناطق
    public function index()
    {
        $zones = Zone::all();
        return view('zones.index', compact('zones'));
    }

    // عرض نموذج إضافة منطقة جديدة
    public function create()
    {
        return view('zones.create');
    }

    // حفظ منطقة جديدة
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:zones,name',
            'code' => 'required|string|max:255|unique:zones,code',
            'description' => 'nullable|string',
        ]);

        Zone::create($data);

        return redirect()->route('zones.index')->with('success', 'تمت إضافة المنطقة بنجاح.');
    }

    // عرض تفاصيل منطقة معينة
    public function show(Zone $zone)
    {
        return view('zones.show', compact('zone'));
    }

    // عرض نموذج تعديل المنطقة
    public function edit(Zone $zone)
    {
        return view('zones.edit', compact('zone'));
    }

    // تحديث بيانات المنطقة
    public function update(Request $request, Zone $zone)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:zones,name,' . $zone->id,
            'code' => 'required|string|max:255|unique:zones,code,' . $zone->id,
            'description' => 'nullable|string',
        ]);

        $zone->update($data);

        return redirect()->route('zones.index')->with('success', 'تم تحديث المنطقة بنجاح.');
    }

    // حذف المنطقة
    public function destroy(Zone $zone)
    {
        $zone->delete();

        return redirect()->route('zones.index')->with('success', 'تم حذف المنطقة بنجاح.');
    }
}
