<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
   

    // عرض الصفحة الرئيسية لإنشاء التقارير
    public function index()
    {
        // يمكنك تمرير النماذج والحقول المتاحة من هنا
        $models = [
            'Product' => 'المنتجات',
            'Warehouse' => 'المخازن',
            'InventoryTransaction' => 'الحركات المخزنية',
        ];
        
    
        $fieldNames = [
            'id' => 'المعرف',
            'name' => 'الاسم',
            'quantity' => 'الكمية',
            'price' => 'السعر',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'آخر تحديث'
        ];
        
        return view('reports.index', compact('models', 'fieldNames'));
    }

    // إنشاء التقرير بناءً على مدخلات المستخدم
    public function generate(Request $request)
    {
        $models = ['Product', 'Warehouse', 'InventoryTransaction'];
        $modelName = $request->input('model');

        // التأكد من أن الموديل موجود في القائمة لتجنب أي استدعاءات خاطئة
        if (!in_array($modelName, $models)) {
            return back()->with('error', 'الموديل المحدد غير صالح');
        }

        $model = "App\\Models\\$modelName";
        $fields = $request->input('fields', []);
        $conditions = $request->input('conditions', []);

        // تنفيذ الاستعلام
        $query = $model::query();
        foreach ($conditions as $condition) {
            $query->where($condition['field'], $condition['operator'], $condition['value']);
        }

        $results = $query->get($fields);

        return view('reports.result', compact('results', 'fields'));
    }
}
