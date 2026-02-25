<?php

namespace App\Http\Controllers;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderItem;
use App\Models\Product;
use App\Models\Partner;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ReturnOrderController extends Controller
{
    // public function index(Request $request)
    // {
    //     $returnOrders = ReturnOrder::with('customer', 'items.product') // جلب بيانات العميل والعناصر مع المنتجات

    //         ->when($request->search, function ($query) use ($request) {
    //             $search = $request->search;

    //             // البحث داخل جدول `return_orders`
    //             $query->where('return_number', 'like', '%' . $search . '%')
    //                 ->orWhere('return_reason', 'like', '%' . $search . '%')
    //                 ->orWhere('return_date', 'like', '%' . $search . '%')

    //                 // البحث في اسم العميل (Partner model)
    //                 ->orWhereHas('customer', function ($q) use ($search) {
    //                 $q->where('name', 'like', '%' . $search . '%');
    //             })

    //                 // البحث في المنتجات المرتبطة عبر الجدول الوسيط
    //                 ->orWhereHas('items.product', function ($q) use ($search) {
    //                 $q->where('name', 'like', '%' . $search . '%');
    //             });
    //         })
    //         ->paginate(10);  // تحديد عدد العناصر لكل صفحة

    //     return view('returns-management.index', compact('returnOrders'));
    // }
    public function index(Request $request)
{
    $returnOrders = ReturnOrder::with('customer', 'items.product')
        ->when($request->search, function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('return_number', 'like', '%' . $search . '%')
                    ->orWhere('return_reason', 'like', '%' . $search . '%')
                    ->orWhere('return_date', 'like', '%' . $search . '%')
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('items.product', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
            });
        })
        ->when($request->status, function ($query) use ($request) {
            $allowedStatuses = ['معلق', 'مكتمل', 'ملغي']; // الحالات المتوفرة
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        // dd($returnOrders->first()->status);
        return view('returns-management.index', compact('returnOrders'));
}


    public function show(string $id, Request $request)
    {
        // جلب المرتجع مع العناصر المرتبطة
        $returnOrder = ReturnOrder::with('items', 'customer') 
            ->where('id', $id) // تصفية حسب رقم المرتجع
            ->firstOrFail(); // جلب أول مرتجع أو إرجاع خطأ إذا لم يتم العثور عليه

        // بناء الاستعلام لتصفية العناصر المرتبطة بالمرتجع مع المنتج
        $returnOrderItems = ReturnOrderItem::with('product')
            ->where('return_order_id', $id) // تصفية حسب رقم المرتجع
            ->where('Is_Send', 0); // تصفية حسب حالة الإرسال

        // إذا كان هناك نص بحث، قم بتصفية العناصر بناءً على اسم المنتج
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search); // تحويل البحث إلى أحرف صغيرة للمقارنة
            $returnOrderItems = $returnOrderItems->whereHas('product', function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']);
            });
        }

        $items = $returnOrderItems->paginate(10); // استخدم paginate على الاستعلام

        // إذا لم يتم العثور على العناصر
        if ($items->isEmpty() && $returnOrderItems->count() == 0) {
            return redirect()->route('returns.process.index')->with('error', 'المرتجع غير موجود أو لا يحتوي على عناصر');
        }

        // إرجاع العرض مع البيانات
        return view('returns-management.show', compact('returnOrder', 'items'));
    }

    public function create()
    {
        // جلب قائمة العملاء
        $customers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 2); // نوع العميل
        })->get();
        
        // جلب قائمة المنتجات
        $products = Product::all();
        
        return view('returns-management.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:partners,id',
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
            // إنشاء سجل جديد في جدول return_orders
            $returnOrder = ReturnOrder::create([
                'customer_id' => $validatedData['customer_id'],
                'return_number' => 'RET-' . time(), // إنشاء رقم فريد للمرتجع
                'return_reason' => $validatedData['return_reason'],
                'return_date' => $validatedData['return_date'],
                'status' => 'معلق', // الحالة الافتراضية للمرتجع
            ]);
            
            // إضافة المنتجات المرتجعة إلى جدول return_order_items
            foreach ($validatedData['items'] as $item) {
                $returnOrderItem = ReturnOrderItem::create([
                    'return_order_id' => $returnOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'return_reason' => $item['return_reason'] ?? $validatedData['return_reason'],
                    'Is_Send' => 0, // لم يتم إرسالها بعد
                ]);
                
                // إنشاء حركة مخزنية للمنتج المرتجع (إضافة للمخزون)
                $this->createInventoryTransaction($returnOrderItem);
            }
            
            // تأكيد المعاملة
            DB::commit();
            
            return redirect()->route('returns.process.index')->with('success', 'تم إنشاء المرتجع بنجاح');
        } catch (\Exception $e) {
            // التراجع عن المعاملة في حالة حدوث خطأ
            DB::rollBack();
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المرتجع: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        // جلب المرتجع مع العناصر المرتبطة
        $returnOrder = ReturnOrder::with('items.product', 'customer')->findOrFail($id);
        
        // جلب قائمة العملاء
        $customers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 2); // نوع العميل
        })->get();
        
        // جلب قائمة المنتجات
        $products = Product::all();
        
        return view('returns-management.edit', compact('returnOrder', 'customers', 'products'));
    }
    
    public function update(Request $request, $id)
    {
        // التحقق من البيانات المدخلة
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:partners,id',
            'return_reason' => 'required|string',
            'return_date' => 'required|date',
            'status' => 'required|string',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:return_order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.return_reason' => 'nullable|string',
        ]);
        
        // بدء المعاملة لضمان تكامل البيانات
        DB::beginTransaction();
        
        try {
            // جلب المرتجع
            $returnOrder = ReturnOrder::findOrFail($id);
            
            // تحديث بيانات المرتجع
            $returnOrder->update([
                'customer_id' => $validatedData['customer_id'],
                'return_reason' => $validatedData['return_reason'],
                'return_date' => $validatedData['return_date'],
                'status' => $validatedData['status'],
            ]);
            
            // الحصول على قائمة العناصر الحالية
            $currentItemIds = $returnOrder->items->pluck('id')->toArray();
            $updatedItemIds = [];
            
            // تحديث العناصر الموجودة وإضافة العناصر الجديدة
            foreach ($validatedData['items'] as $itemData) {
                if (isset($itemData['id']) && in_array($itemData['id'], $currentItemIds)) {
                    // تحديث العنصر الموجود
                    $item = ReturnOrderItem::findOrFail($itemData['id']);
                    
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
                    $newItem = ReturnOrderItem::create([
                        'return_order_id' => $returnOrder->id,
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'return_reason' => $itemData['return_reason'] ?? $validatedData['return_reason'],
                        'Is_Send' => 0, // لم يتم إرسالها بعد
                    ]);
                    
                    // إنشاء حركة مخزنية للمنتج المرتجع الجديد
                    $this->createInventoryTransaction($newItem);
                    
                    $updatedItemIds[] = $newItem->id;
                }
            }
            
            // حذف العناصر التي لم تعد موجودة
            $itemsToDelete = array_diff($currentItemIds, $updatedItemIds);
            foreach ($itemsToDelete as $itemId) {
                $item = ReturnOrderItem::findOrFail($itemId);
                
                // حذف حركة المخزون المرتبطة
                $this->deleteInventoryTransaction($item);
                
                // حذف العنصر
                $item->delete();
            }
            
            // تأكيد المعاملة
            DB::commit();
            
            return redirect()->route('returns.process.show', $returnOrder->id)->with('success', 'تم تحديث المرتجع بنجاح');
        } catch (\Exception $e) {
            // التراجع عن المعاملة في حالة حدوث خطأ
            DB::rollBack();
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المرتجع: ' . $e->getMessage())->withInput();
        }
    }
    
    public function destroy($id)
    {
        // بدء المعاملة لضمان تكامل البيانات
        DB::beginTransaction();
        
        try {
            // جلب المرتجع مع العناصر المرتبطة
            $returnOrder = ReturnOrder::with('items')->findOrFail($id);
            
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
            
            return redirect()->route('returns.process.index')->with('success', 'تم حذف المرتجع بنجاح');
        } catch (\Exception $e) {
            // التراجع عن المعاملة في حالة حدوث خطأ
            DB::rollBack();
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المرتجع: ' . $e->getMessage());
        }
    }
    
    /**
     * عرض تقارير المرتجعات
     */
    public function reports(Request $request)
    {
        // تحديد الفترة الزمنية للتقرير
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // إحصائيات عامة
        $totalReturns = ReturnOrder::whereBetween('return_date', [$startDate, $endDate])->count();
        $totalReturnItems = ReturnOrderItem::whereHas('returnOrder', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('return_date', [$startDate, $endDate]);
        })->count();
        
        // المنتجات الأكثر إرجاعاً
        $topReturnedProducts = ReturnOrderItem::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('returnOrder', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('return_date', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
        
        // أسباب الإرجاع الأكثر شيوعاً
        $topReturnReasons = ReturnOrder::select('return_reason', DB::raw('COUNT(*) as count'))
            ->whereBetween('return_date', [$startDate, $endDate])
            ->groupBy('return_reason')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        // إحصائيات حسب العملاء
        $customerReturns = ReturnOrder::with('customer')
            ->select('customer_id', DB::raw('COUNT(*) as count'))
            ->whereBetween('return_date', [$startDate, $endDate])
            ->groupBy('customer_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // إحصائيات حسب الحالة - تم تعطيلها مؤقتًا لأن عمود status غير موجود
        $returnsByStatus = collect(); // إرجاع مجموعة فارغة بدلاً من الاستعلام
        
        return view('returns-management.reports', compact(
            'totalReturns',
            'totalReturnItems',
            'topReturnedProducts',
            'topReturnReasons',
            'customerReturns',
            'returnsByStatus',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * عرض تقارير مرتجعات العملاء
     */
    public function customerReports(Request $request)
    {
        // تحديد الفترة الزمنية للتقرير
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $customerId = $request->input('customer_id');
        
        // جلب قائمة العملاء
        $customers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 2); // نوع العميل
        })->get();
        
        // بناء الاستعلام
        $query = ReturnOrder::with('customer', 'items.product')
            ->whereBetween('return_date', [$startDate, $endDate]);
        
        // تصفية حسب العميل إذا تم تحديده
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }
        
        // تنفيذ الاستعلام
        $returns = $query->orderBy('return_date', 'desc')->paginate(10);
        
        // إحصائيات للعميل المحدد
        $customerStats = null;
        if ($customerId) {
            $customerStats = [
                'total_returns' => ReturnOrder::where('customer_id', $customerId)
                    ->whereBetween('return_date', [$startDate, $endDate])
                    ->count(),
                'total_items' => ReturnOrderItem::whereHas('returnOrder', function ($query) use ($customerId, $startDate, $endDate) {
                    $query->where('customer_id', $customerId)
                        ->whereBetween('return_date', [$startDate, $endDate]);
                })->sum('quantity'),
                'top_products' => ReturnOrderItem::with('product')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->whereHas('returnOrder', function ($query) use ($customerId, $startDate, $endDate) {
                        $query->where('customer_id', $customerId)
                            ->whereBetween('return_date', [$startDate, $endDate]);
                    })
                    ->groupBy('product_id')
                    ->orderByDesc('total_quantity')
                    ->limit(5)
                    ->get(),
            ];
        }
        
        return view('returns-management.customer-reports', compact(
            'returns',
            'customers',
            'customerId',
            'startDate',
            'endDate',
            'customerStats'
        ));
    }
    
    /**
     * طباعة المرتجع
     */
    public function print($id)
    {
        // جلب المرتجع مع العناصر المرتبطة
        $returnOrder = ReturnOrder::with(['items.product', 'customer'])
            ->findOrFail($id);
            
        return view('returns-management.print', compact('returnOrder'));
    }
    
    /**
     * إنشاء حركة مخزنية للمنتج المرتجع
     */
    private function createInventoryTransaction(ReturnOrderItem $item)
    {
        // الحصول على المستودع الافتراضي
        $defaultWarehouse = \App\Models\Warehouse::first();
        
        // إنشاء حركة مخزنية (إضافة للمخزون)
        InventoryTransaction::create([
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'warehouse_id' => $defaultWarehouse ? $defaultWarehouse->id : 1, // المستودع الافتراضي
            'type' => 'return', // نوع الحركة: مرتجع
            'reference_id' => $item->id,
            'reference_type' => 'App\Models\ReturnOrderItem',
            'transaction_type_id' => 1, // إضافة حقل نوع المعاملة (1 = إضافة للمخزون)
            'notes' => 'إضافة مخزون من مرتجع العميل: ' . ($item->returnOrder->customer->name ?? 'غير محدد'),
            'created_user' => auth()->id() ?? 1,
            'updated_user' => auth()->id() ?? 1,
            'transaction_date'     => now(), 
        ]);
        
        // تحديث كمية المنتج في المخزون
        $product = Product::findOrFail($item->product_id);
        $product->increment('stock_quantity', $item->quantity);
    }
    
    /**
     * تحديث حركة المخزون للمنتج المرتجع
     */
    private function updateInventoryTransaction(ReturnOrderItem $item, $oldQuantity)
    {
        // البحث عن حركة المخزون المرتبطة
        $transaction = InventoryTransaction::where('reference_id', $item->id)
            ->where('reference_type', 'App\Models\ReturnOrderItem')
            ->first();
        
        if ($transaction) {
            // التأكد من وجود warehouse_id
            if (!$transaction->warehouse_id) {
                $defaultWarehouse = \App\Models\Warehouse::first();
                $transaction->warehouse_id = $defaultWarehouse ? $defaultWarehouse->id : 1;
            }
            
            // حساب الفرق في الكمية
            $quantityDiff = $item->quantity - $oldQuantity;
            
            // تحديث حركة المخزون
            $transaction->update([
                'quantity' => $item->quantity,
                'transaction_type_id' => 1, // إضافة حقل نوع المعاملة (1 = إضافة للمخزون)
                'notes' => 'تحديث مخزون من مرتجع العميل: ' . ($item->returnOrder->customer->name ?? 'غير محدد'),
                'updated_user' => auth()->id() ?? 1,
            ]);
            
            // تحديث كمية المنتج في المخزون
            $product = Product::findOrFail($item->product_id);
            if ($quantityDiff > 0) {
                $product->increment('stock_quantity', $quantityDiff);
            } elseif ($quantityDiff < 0) {
                $product->decrement('stock_quantity', abs($quantityDiff));
            }
        } else {
            // إنشاء حركة مخزون جديدة إذا لم تكن موجودة
            $this->createInventoryTransaction($item);
        }
    }
    
    /**
     * حذف حركة المخزون للمنتج المرتجع
     */
    private function deleteInventoryTransaction(ReturnOrderItem $item)
    {
        // البحث عن حركة المخزون المرتبطة باستخدام حقل reference
        $transaction = InventoryTransaction::where('reference', 'RETURN-CUSTOMER-' . $item->id)
            ->orWhere('notes', 'like', '%مرتجع العميل%' . $item->id . '%')
            ->first();
        
        if ($transaction) {
            // تحديث كمية المنتج في المخزون (إزالة الكمية المضافة سابقاً)
            $product = Product::findOrFail($item->product_id);
            $product->decrement('stock_quantity', $item->quantity);
            
            // حذف حركة المخزون
            $transaction->delete();
        }
    }
}
