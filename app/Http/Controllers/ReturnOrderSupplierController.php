<?php

namespace App\Http\Controllers;

use App\Models\ReturnSuppliersOrder;
use App\Models\ReturnSuppliersOrderItem;
use App\Models\Product;
use App\Models\Partner;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class ReturnOrderSupplierController extends Controller
{
    // public function index(Request $request)
    // {
    //     $returnOrders = ReturnSuppliersOrder::with('supplier', 'items.product')
    //         ->when($request->search, function ($query) use ($request) {
    //             $search = $request->search;
                
    //             // البحث داخل جدول `return_suppliers_orders`
    //             $query
    //                 ->Where('return_reason', 'like', '%' . $search . '%')
    //                 ->orWhere('created_at', 'like', '%' . $search . '%')
                    
    //                 // البحث في اسم المورد
    //                 ->orWhereHas('supplier', function ($q) use ($search) {
    //                     $q->where('name', 'like', '%' . $search . '%');
    //                 })
                    
    //                 // البحث في المنتجات المرتبطة
    //                 ->orWhereHas('items.product', function ($q) use ($search) {
    //                     $q->where('name', 'like', '%' . $search . '%');
    //                 });
                    
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);
            
    //     return view('returns-management.suppliers.index', compact('returnOrders'));
    // }
    public function index(Request $request)
    {
        $returnOrders = ReturnSuppliersOrder::with('supplier', 'items.product')
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                
                $query->where(function ($q) use ($search) {
                    $q->where('return_reason', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhere('return_number', 'like', '%' . $search . '%') 
                        ->orWhereHas('supplier', function ($q2) use ($search) {
                            $q2->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('items.product', function ($q2) use ($search) {
                            $q2->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        return view('returns-management.suppliers.index', compact('returnOrders'));
    }

    public function create()
    {
        // جلب قائمة الموردين
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 1); // نوع المورد
        })->get();
        
        // جلب قائمة المنتجات
        $products = Product::all();
        
        return view('returns-management.suppliers.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:partners,id',
            'return_reason' => 'required|string',
            'return_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.return_reason' => 'nullable|string',
        ]);
        
        // بدء المعاملة لضمان تكامل البيانات
        DB::beginTransaction();
        
        try {
            // إنشاء سجل جديد في جدول return_suppliers_orders
            $returnOrder = ReturnSuppliersOrder::create([
                'supplier_id' => $validatedData['supplier_id'],
                'return_number' => 'SRET-' . time(), // إنشاء رقم فريد للمرتجع
                'return_reason' => $validatedData['return_reason'],
                'status' => 'قيد المراجعة', // الحالة الافتراضية للمرتجع
                'return_date' => now()->format('Y-m-d'), // إضافة تاريخ المرتجع
            ]);
            
            // إضافة المنتجات المرتجعة إلى جدول return_suppliers_order_items
            foreach ($validatedData['items'] as $item) {
                $returnOrderItem = ReturnSuppliersOrderItem::create([
                    'return_supplier_order_id' => $returnOrder->id, // تصحيح اسم الحقل ليتوافق مع نموذج البيانات
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'status' => 'قيد المراجعة', // إضافة حقل الحالة
                    'return_reason' => $item['return_reason'] ?? $validatedData['return_reason'],
                    'is_sent' => 0, // لم يتم إرسالها بعد
                ]);
                
                // إنشاء حركة مخزنية للمنتج المرتجع (خصم من المخزون)
                $this->createInventoryTransaction($returnOrderItem);
            }
            
            // تأكيد المعاملة
            DB::commit();
            
            return redirect()->route('returns-suppliers.index')->with('success', 'تم إنشاء مرتجع المورد بنجاح');
        } catch (\Exception $e) {
            // التراجع عن المعاملة في حالة حدوث خطأ
            DB::rollBack();
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء مرتجع المورد: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        // جلب المرتجع مع العناصر المرتبطة
        $returnOrder = ReturnSuppliersOrder::with('items.product', 'supplier')->findOrFail($id);
        
        return view('returns-management.suppliers.show', compact('returnOrder'));
    }

    public function edit($id)
    {
        // جلب المرتجع مع العناصر المرتبطة
        $returnOrder = ReturnSuppliersOrder::with('items.product', 'supplier')->findOrFail($id);
        
        // جلب قائمة الموردين
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 1); // نوع المورد
        })->get();
        
        // جلب قائمة المنتجات
        $products = Product::all();
        
        return view('returns-management.suppliers.edit', compact('returnOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {
        // التحقق من البيانات المدخلة
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:partners,id',
            'return_reason' => 'required|string',
            'status' => 'required|string',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:return_suppliers_order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.return_reason' => 'nullable|string',
        ]);
        
        // بدء المعاملة لضمان تكامل البيانات
        DB::beginTransaction();
        
        try {
            // جلب المرتجع
            $returnOrder = ReturnSuppliersOrder::findOrFail($id);
            
            // تحديث بيانات المرتجع
            $returnOrder->update([
                'supplier_id' => $validatedData['supplier_id'],
                'return_reason' => $validatedData['return_reason'],
                'status' => $validatedData['status'],
            ]);
            
            // الحصول على قائمة العناصر الحالية
            $currentItemIds = $returnOrder->items->pluck('id')->toArray();
            $updatedItemIds = [];
            
            // تحديث العناصر الموجودة وإضافة العناصر الجديدة
            foreach ($validatedData['items'] as $itemData) {
                if (isset($itemData['id']) && in_array($itemData['id'], $currentItemIds)) {
                    // تحديث العنصر الموجود
                    $item = ReturnSuppliersOrderItem::findOrFail($itemData['id']);
                    
                    // حفظ الكمية القديمة قبل التحديث
                    $oldQuantity = $item->quantity;
                    
                    $item->update([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'return_reason' => $itemData['return_reason'] ?? $validatedData['return_reason'],
                    ]);
                    
                    // تحديث حركة المخزون إذا تغيرت الكمية
                    if ($oldQuantity != $itemData['quantity']) {
                        $this->updateInventoryTransaction($item, $oldQuantity);
                    }
                    
                    $updatedItemIds[] = $item->id;
                } else {
                    // إضافة عنصر جديد
                    $newItem = ReturnSuppliersOrderItem::create([
                        'return_suppliers_order_id' => $returnOrder->id,
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'return_reason' => $itemData['return_reason'] ?? $validatedData['return_reason'],
                        'is_sent' => 0, // لم يتم إرسالها بعد
                    ]);
                    
                    // إنشاء حركة مخزنية للمنتج المرتجع الجديد
                    $this->createInventoryTransaction($newItem);
                    
                    $updatedItemIds[] = $newItem->id;
                }
            }
            
            // حذف العناصر التي لم تعد موجودة
            $itemsToDelete = array_diff($currentItemIds, $updatedItemIds);
            foreach ($itemsToDelete as $itemId) {
                $item = ReturnSuppliersOrderItem::findOrFail($itemId);
                
                // حذف حركة المخزون المرتبطة
                $this->deleteInventoryTransaction($item);
                
                // حذف العنصر
                $item->delete();
            }
            
            // تأكيد المعاملة
            DB::commit();
            
            return redirect()->route('returns-suppliers.edit', $returnOrder->id)
            ->with('success', 'تم تحديث مرتجع المورد بنجاح');
    } catch (\Exception $e) {
            // التراجع عن المعاملة في حالة حدوث خطأ
            DB::rollBack();
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث مرتجع المورد: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        // بدء المعاملة لضمان تكامل البيانات
        DB::beginTransaction();
        
        try {
            // جلب المرتجع مع العناصر المرتبطة
            $returnOrder = ReturnSuppliersOrder::with('items')->findOrFail($id);
            
            // حذف حركات المخزون المرتبطة بالعناصر
            foreach ($returnOrder->items as $item) {
                $this->deleteInventoryTransaction($item);
            }
            
            // حذف العناصر المرتبطة
            $returnOrder->items()->delete();
            
            // حذف المرتجع
            $returnOrder->delete();
            
            // تأكيد المعاملة
            DB::commit();
            
            return redirect()->route('returns-suppliers.index')->with('success', 'تم حذف مرتجع المورد بنجاح');
        } catch (\Exception $e) {
            // التراجع عن المعاملة في حالة حدوث خطأ
            DB::rollBack();
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف مرتجع المورد: ' . $e->getMessage());
        }
    }

    public function send($id)
    {
        // بدء المعاملة لضمان تكامل البيانات
        DB::beginTransaction();
        
        try {
            // جلب المرتجع
            $returnOrder = ReturnSuppliersOrder::with('items')->findOrFail($id);
            
            // تحديث حالة المرتجع
            $returnOrder->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            
            // تحديث حالة العناصر
            foreach ($returnOrder->items as $item) {
                $item->update([
                    'is_sent' => 1,
                ]);
            }
            
            // تأكيد المعاملة
            DB::commit();
            
            return redirect()->route('returns.suppliers.show', $returnOrder->id)->with('success', 'تم إرسال مرتجع المورد بنجاح');
        } catch (\Exception $e) {
            // التراجع عن المعاملة في حالة حدوث خطأ
            DB::rollBack();
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال مرتجع المورد: ' . $e->getMessage());
        }
    }

    public function printPdf($id)
    {
        // جلب المرتجع مع العناصر المرتبطة
        $returnOrder = ReturnSuppliersOrder::with(['items.product', 'supplier'])->findOrFail($id);
        
        // عرض صفحة HTML مباشرة بدلاً من PDF لحل مشكلة عرض النص العربي
        return View::make('returns-suppliers.returnOrderspdf', compact('returnOrder'));
    }

    /**
     * طباعة مرتجع المورد
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        return $this->printPdf($id);
    }

    /**
     * عرض تقارير مرتجعات الموردين
     */
    public function reports(Request $request)
    {
        // تحديد الفترة الزمنية للتقرير
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // إحصائيات عامة
        $totalReturns = ReturnSuppliersOrder::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
        $totalReturnItems = ReturnSuppliersOrderItem::whereHas('returnSuppliersOrder', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })->count();
        
        // المنتجات الأكثر إرجاعاً
        $topReturnedProducts = ReturnSuppliersOrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('returnSuppliersOrder', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
        
        // أسباب الإرجاع الأكثر شيوعاً
        $topReturnReasons = ReturnSuppliersOrder::select('return_reason', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('return_reason')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        // إحصائيات حسب الموردين
        $supplierReturns = ReturnSuppliersOrder::with('supplier')
            ->select('supplier_id', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('supplier_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // إحصائيات حسب الحالة
        $returnsByStatus = ReturnSuppliersOrder::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();
        
        return view('returns-management.reports', compact(
            'totalReturns',
            'totalReturnItems',
            'topReturnedProducts',
            'topReturnReasons',
            'supplierReturns',
            'returnsByStatus',
            'startDate',
            'endDate'
        ))->with('customerReturns', $supplierReturns); // إضافة متغير customerReturns ليتوافق مع الصفحة
    }
    
    /**
     * عرض تقارير مرتجعات الموردين حسب المورد
     */
    public function supplierReports(Request $request)
    {
        // تحديد الفترة الزمنية للتقرير
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $supplierId = $request->input('supplier_id');
        
        // جلب قائمة الموردين
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 1); // نوع المورد
        })->get();
        
        // بناء الاستعلام
        $query = ReturnSuppliersOrder::with('supplier', 'items.product')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        // تصفية حسب المورد إذا تم تحديده
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }
        
        // تنفيذ الاستعلام
        $returns = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // إحصائيات للمورد المحدد
        $supplierStats = null;
        if ($supplierId) {
            $supplierStats = [
                'total_returns' => ReturnSuppliersOrder::where('supplier_id', $supplierId)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->count(),
                'total_items' => ReturnSuppliersOrderItem::whereHas('returnSuppliersOrder', function ($query) use ($supplierId, $startDate, $endDate) {
                    $query->where('supplier_id', $supplierId)
                        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                })->sum('quantity'),
                'top_products' => ReturnSuppliersOrderItem::with('product')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->whereHas('returnSuppliersOrder', function ($query) use ($supplierId, $startDate, $endDate) {
                        $query->where('supplier_id', $supplierId)
                            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->groupBy('product_id')
                    ->orderByDesc('total_quantity')
                    ->limit(5)
                    ->get(),
            ];
        }
        
        return view('returns-management.suppliers.supplier-reports', compact(
            'returns',
            'suppliers',
            'supplierId',
            'startDate',
            'endDate',
            'supplierStats'
        ));
    }
    
    /**
     * إنشاء حركة مخزنية للمنتج المرتجع للمورد
     */
    private function createInventoryTransaction(ReturnSuppliersOrderItem $item)
    {
        // الحصول على المستودع الافتراضي
        $defaultWarehouse = \App\Models\Warehouse::first();

        // إنشاء حركة مخزنية (خصم من المخزون)
        InventoryTransaction::create([
            'product_id' => $item->product_id,
            'quantity' => -$item->quantity, // قيمة سالبة لأنها خصم من المخزون
            'warehouse_id' => $defaultWarehouse ? $defaultWarehouse->id : 1, // المستودع الافتراضي
            'type' => 'supplier_return', // نوع الحركة: مرتجع للمورد
            'reference_id' => $item->id,
            'reference_type' => 'App\Models\ReturnSuppliersOrderItem',
            'transaction_type_id' => 2, // خصم من المخزون
            'notes' => 'خصم مخزون من مرتجع المورد: ' . ($item->returnSuppliersOrder->supplier->name ?? 'غير محدد'),
            'transaction_date' => now(),
            'created_user' => auth()->id() ?? 1,
            'updated_user' => auth()->id() ?? 1,
        ]);
        
        // تحديث كمية المنتج في المخزون
        $product = Product::findOrFail($item->product_id);
        $product->decrement('stock_quantity', $item->quantity);
    }
    
    /**
     * تحديث حركة المخزون للمنتج المرتجع للمورد
     */
    private function updateInventoryTransaction(ReturnSuppliersOrderItem $item, $oldQuantity)
    {
        // البحث عن حركة المخزون المرتبطة
        $transaction = InventoryTransaction::where('reference_id', $item->id)
            ->where('reference_type', 'App\Models\ReturnSuppliersOrderItem')
            ->first();
        
        if ($transaction) {
            // التأكد من وجود warehouse_id
            if (!$transaction->warehouse_id) {
                $defaultWarehouse = \App\Models\Warehouse::first();
                $transaction->warehouse_id = $defaultWarehouse ? $defaultWarehouse->id : 1;
            }
            
            // التأكد من وجود transaction_type_id
            if (!$transaction->transaction_type_id) {
                $transaction->transaction_type_id = 2; // خصم من المخزون
            }
            
            // حساب الفرق في الكمية
            $quantityDiff = $item->quantity - $oldQuantity;
            
            // تحديث حركة المخزون
            $transaction->update([
                'quantity' => -$item->quantity, // قيمة سالبة لأنها خصم من المخزون
                'notes' => 'تحديث مخزون من مرتجع المورد: ' . ($item->returnOrder->supplier->name ?? 'غير محدد'),
            ]);
            
            // تحديث كمية المنتج في المخزون
            $product = Product::findOrFail($item->product_id);
            if ($quantityDiff > 0) {
                // إذا زادت الكمية المرتجعة، نقوم بخصم الفرق من المخزون
                $product->decrement('stock_quantity', $quantityDiff);
            } elseif ($quantityDiff < 0) {
                // إذا نقصت الكمية المرتجعة، نقوم بإضافة الفرق إلى المخزون
                $product->increment('stock_quantity', abs($quantityDiff));
            }
        } else {
            // إنشاء حركة مخزون جديدة إذا لم تكن موجودة
            $this->createInventoryTransaction($item);
        }
    }
    
    /**
     * حذف حركة المخزون للمنتج المرتجع للمورد
     */
    private function deleteInventoryTransaction(ReturnSuppliersOrderItem $item)
    {
        // البحث عن حركة المخزون المرتبطة باستخدام حقل reference
        $transaction = InventoryTransaction::where('reference', 'RETURN-SUPPLIER-' . $item->id)
            ->orWhere('notes', 'like', '%مرتجع المورد%' . $item->id . '%')
            ->first();
        
        if ($transaction) {
            // تحديث كمية المنتج في المخزون (إضافة الكمية المخصومة سابقاً)
            $product = Product::findOrFail($item->product_id);
            $product->increment('stock_quantity', $item->quantity);
            
            // حذف حركة المخزون
            $transaction->delete();
        }
    }
}
