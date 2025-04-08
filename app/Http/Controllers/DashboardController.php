<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
    
        // المستودعات المتاحة للمستخدم
        $accessibleWarehouses = $user->roles()
            ->with('warehouses')
            ->get()
            ->flatMap(fn($role) => $role->warehouses)
            ->unique('id');
    
        // محاولة جلب المستودع المختار من الطلب أو من الجلسة
        if ($request->has('warehouse_id')) {
            $selectedWarehouseId = $request->input('warehouse_id');
            session(['selected_warehouse_id' => $selectedWarehouseId]); // تخزين في session
        } else {
            $selectedWarehouseId = session('selected_warehouse_id');
        }
    
        // إذا لم يتم اختيار مستودع مسبقًا، نحدد القيمة الافتراضية
        if (!$selectedWarehouseId && $accessibleWarehouses->count() === 1) {
            $selectedWarehouseId = $accessibleWarehouses->first()->id;
            session(['selected_warehouse_id' => $selectedWarehouseId]);
        }
    
        // حماية: إذا المستودع المحدد لا ينتمي للمستخدم، نعيد التعيين
        if (!$accessibleWarehouses->pluck('id')->contains($selectedWarehouseId)) {
            $selectedWarehouseId = $accessibleWarehouses->first()?->id;
            session(['selected_warehouse_id' => $selectedWarehouseId]);
        }


       
        $categories = Category::withCount([
            'products as products_count'
        ])->get();
        
    
        $categoryLabels = $categories->pluck('name')->toArray();
        $categoryCounts = $categories->pluck('products_count')->toArray();
    

        // المنتجات المتاحة: لها سجل في inventory و الكمية > 0
        $storedCount = Inventory::where('warehouse_id', $selectedWarehouseId)
            ->where('quantity', '>', 0)
            ->count();
        
        // جميع المنتجات المرتبطة بهذا المستودع
        $productIdsInWarehouse = Inventory::where('warehouse_id', $selectedWarehouseId)->pluck('id');
        
        // المنتجات غير المتاحة: إما لا يوجد لها سجل في inventory أو الكمية = 0
        $missingCount = $productIdsInWarehouse->filter(function ($productId) use ($selectedWarehouseId) {
            $inventory = Inventory::where('warehouse_id', $selectedWarehouseId)
                ->where('product_id', $productId)
                ->first();
            
            return !$inventory || $inventory->quantity <= 0;
        })->count();
       
         // الكميات المتوقعة للمنتجات من واقع الحركات
         $availableProducts = DB::table('inventory_transaction_items')
         ->join('inventory_transactions', 'inventory_transaction_items.id', '=', 'inventory_transactions.id')
         ->where('inventory_transactions.warehouse_id', $selectedWarehouseId)
         ->select('inventory_transaction_items.product_id', DB::raw('SUM(inventory_transaction_items.quantity * inventory_transactions.effect) as total_quantity'))
         ->groupBy('inventory_transaction_items.product_id')
         ->having('total_quantity', '>', 0)
         ->get();
     $exactedCount = $availableProducts->count();

     $productCount = $storedCount;
     $expiredCount = $exactedCount;
    //  $expiredCount = Product::where('warehouse_id', $selectedWarehouseId)
    //      ->whereDate('expiration_date', '<', now())->count();
 
        $months = collect(range(0, 5))->map(fn($i) => Carbon::now()->subMonths($i)->format('F'))->reverse()->values();
    
        $inputData = [];
        $outputData = [];
    
        foreach ($months as $i => $monthName) {
            $monthDate = Carbon::now()->subMonths(5 - $i);
    
            $inputData[] = InventoryTransaction::where('warehouse_id', $selectedWarehouseId)
                ->where('effect', '1')
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->count();
    
            $outputData[] = InventoryTransaction::where('warehouse_id', $selectedWarehouseId)
                ->where('effect', '-1')
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->count();
        }
    
        return view('dashboard.index', compact(
            'productCount',
            'expiredCount',
            'categoryLabels',
            'categoryCounts',
            'storedCount',
            'missingCount',
            'months',
            'inputData',
            'outputData',
            'accessibleWarehouses',
            'selectedWarehouseId'
        ));
    }
    
}    