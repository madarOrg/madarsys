<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InventoryTransaction;
use App\Models\Warehouse;
use App\Models\Product;
use Carbon\Carbon;

class TransactionSearch extends Component
{
    use WithPagination;

    // الفلاتر
    public $warehouse_id;
    public $status;
    public $transaction_type;
    public $reference;
    public $product_name;
    public $quantity_from;
    public $quantity_to;
    public $created_at_from;
    public $created_at_to;

    // تحديث البيانات عند تغيير أي مدخل
    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function render()
    {
        // جلب المستودعات والمنتجات للفلاتر
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $products = Product::all();
        $statuses = ['pending', 'completed', 'canceled'];
        $transactionTypes = ['incoming', 'outgoing', 'transfer'];

        // بناء الاستعلام
        $query = InventoryTransaction::query()
        ->select([
            'inventory_transactions.id',
            'inventory_transactions.reference',
            'inventory_transactions.transaction_type_id',
            'inventory_transactions.status',
            'inventory_transactions.created_at',
            'inventory_transactions.warehouse_id',
            'warehouses.name as warehouse_name',  
            'inventory_transaction_items.product_id',
            'products.name as product_name',
            'inventory_transaction_items.quantity'
        ])
        ->leftJoin('warehouses', 'inventory_transactions.warehouse_id', '=', 'warehouses.id')  
        ->leftJoin('inventory_transaction_items', 'inventory_transactions.id', '=', 'inventory_transaction_items.inventory_transaction_id')
        ->leftJoin('products', 'inventory_transaction_items.product_id', '=', 'products.id');  
    
        // تطبيق الفلاتر:

        //  البحث حسب المستودع
        if ($this->warehouse_id) {
            $query->where('inventory_transactions.warehouse_id', $this->warehouse_id);
        }

        //  البحث حسب الحالة
        if ($this->status) {
            $query->where('inventory_transactions.status', $this->status);
        }

        // 🔑 البحث حسب النوع
        if ($this->transaction_type) {
            $query->where('inventory_transactions.transaction_type_id', $this->transaction_type_id);
        }

        // 🔑 البحث حسب الرقم المرجعي
        if ($this->reference) {
            $query->where('inventory_transactions.reference', 'like', '%' . $this->reference . '%');
        }

        // 🔑 البحث حسب المنتج
        if ($this->product_name) {
            $searchTerm = '%' . $this->product_name . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('products.name', 'like', $searchTerm)
                    ->orWhere('products.barcode', 'like', $searchTerm)
                    ->orWhere('products.sku', 'like', $searchTerm)
                    ->orWhere('products.id', $searchTerm);
            });
        }

        // 🔑 البحث حسب الكمية
        if ($this->quantity_from || $this->quantity_to) {
            $from = $this->quantity_from ?? 0;
            $to = $this->quantity_to ?? PHP_INT_MAX;
            $query->whereBetween('inventory_transaction_items.quantity', [$from, $to]);
        }

        // 🔑 البحث حسب تاريخ الإدخال
        if ($this->created_at_from || $this->created_at_to) {
            $from = $this->created_at_from
                ? Carbon::parse($this->created_at_from)->startOfDay()
                : Carbon::minValue();

            $to = $this->created_at_to
                ? Carbon::parse($this->created_at_to)->endOfDay()
                : Carbon::maxValue();

            $query->whereBetween('inventory_transactions.created_at', [$from, $to]);
        }

        // تنفيذ الاستعلام مع الباجيناشن
        $transactions = $query->paginate(10);

        // عرض النتائج
        return view('livewire.transaction-search', compact('warehouses', 'products', 'statuses', 'transactionTypes', 'transactions'));
    }
}
