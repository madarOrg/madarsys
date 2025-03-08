<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryTransaction;

class InventoryReviewController extends Controller
{
    // قائمة الحركات المعلقة مؤقتًا في الذاكرة
    protected $pendingReviews = [
        ['id' => 1, 'transaction_number' => 'T123', 'status' => 'pending', 'reviewer' => 'Admin'],
        ['id' => 2, 'transaction_number' => 'T124', 'status' => 'pending', 'reviewer' => 'User'],
        ['id' => 3, 'transaction_number' => 'T125', 'status' => 'pending', 'reviewer' => 'Admin'],
    ];

    // عرض الحركات المراجعة المعلقة
    public function index()
    {
        return view('inventory-review.index', ['reviews' => $this->pendingReviews]);
    }

    // تحديث حالة المراجعة
    public function updateStatus(Request $request, $id)
    {
        try {
            // البحث عن الحركة المعلقة بواسطة ID
            $review = InventoryTransaction::find($id);
            if ($review) {
                // تحديث الحالة بناءً على القيمة المرسلة من العميل
                $review->status = $request->status;
                $review->save(); // حفظ التغييرات في قاعدة البيانات
            }
    
            return response()->json([
                'message' => 'تم تحديث الحالة بنجاح',
                'status' => $request->status,
                'id' => $id
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء تحديث الحالة: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
