<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Branch;


class UnitController extends Controller
{
    public function index()
{
    $units = Unit::with('parent')->get();
    // $branches = Branch::all(); // جلب جميع الفروع

    return view('units.index', compact('units'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:units,name',
            'parent_unit_id' => 'nullable|exists:units,id',
            'conversion_factor' => 'nullable|numeric',
        ]);

        Unit::create($request->all());
        return redirect()->back()->with('success', 'تمت إضافة الوحدة بنجاح');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:units,id',
            'name' => 'required|unique:units,name,' . $request->id,
            'parent_unit_id' => 'nullable|exists:units,id',
            'conversion_factor' => 'nullable|numeric',
        ]);

        $unit = Unit::findOrFail($request->id);
        $unit->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث الوحدة بنجاح');
    }
}
