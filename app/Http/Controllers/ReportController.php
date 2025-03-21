<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // مثال على الموديلات التي قد تحتاجها
use App\Models\Warehouse;
use App\Models\InventoryTransaction;

class ReportController extends Controller
{
    public function create()
    {
        // النماذج المتاحة للاختيار
        $models = [
            'Product' => 'المنتجات',
            'Warehouse' => 'المخازن',
            'InventoryTransaction' => 'الحركات المخزنية',
        ];

        return view('reports.create', compact('models'));
    }

    // جلب الحقول والشروط للموديل المختار
    public function getFields(Request $request)
    {
        $modelName = $request->input('model');
        $modelClass = "App\\Models\\$modelName";

        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'الموديل غير موجود'], 404);
        }

        // جلب الحقول من الجدول
        $table = (new $modelClass)->getTable();
        $columns = \Schema::getColumnListing($table);

        // ترجمة الحقول باستخدام المصفوفات الخاصة بكل موديل
        $translatedColumns = [];
        foreach ($columns as $column) {
            $translatedColumns[$column] = __("fields.$column", [], 'ar') ?? $column;
        }

        // فرضا إذا كان هناك شروط مرتبطة بالموديل
        $conditions = [
            'status' => 'الحالة',
            'created_at' => 'تاريخ الإنشاء'
        ];

        return response()->json([
            'fields' => $translatedColumns,
            'conditions' => $conditions
        ]);
    }

    public function generate(Request $request)
    {
        $modelName = $request->input('model');
        $fields = $request->input('fields', []);
        $conditions = $request->input('conditions', []);
    
        $modelClass = "App\\Models\\$modelName";
        if (!class_exists($modelClass)) {
            return back()->with('error', 'الموديل غير موجود');
        }
    
        $query = $modelClass::query();
    
        // تأكد من أن الشروط هي مصفوفات تحتوي على الحقول، العوامل والقيم
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                // تأكد من أن الشرط عبارة عن مصفوفة تحتوي على 'field' و 'operator' و 'value'
                if (is_array($condition) && isset($condition['field'], $condition['operator'], $condition['value'])) {
                    $query->where($condition['field'], $condition['operator'], $condition['value']);
                }
            }
        }
    
        // استعلام مع الحقول المحددة
        $results = $query->get($fields);
    
        return view('reports.result', compact('results', 'fields'));
    }
    
}
