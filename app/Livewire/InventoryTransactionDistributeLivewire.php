<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\InventoryTransaction\InventoryTransactionService;
use App\Models\InventoryTransaction;
use App\Models\InventoryProduct;

class InventoryTransactionDistributeLivewire extends Component
{
    protected $inventoryTransactionService;
    public $transactions = [];
    public $distribution = [];
    public $selectedTransactionId = null;
    public $selectedTransaction = null;
    public $search = '';
    protected $listeners = ['transactionUpdated' => '$refresh']; 
    
    public function __construct()
    {
        $this->inventoryTransactionService = new InventoryTransactionService();
    }



    // استخدام mount لتقبل المعاملات
    public function mount($transactions)
    {
    // dd($transactions);
        $this->transactions = $transactions;


        $this->loadTransactions();
        if (!empty($this->transactions)) {
            $this->selectedTransactionId = $this->transactions[0]['id'];

            $this->loadTransactionDetails();
        }


    }

    public function loadTransactions()
    {
        $this->transactions = InventoryTransaction::with([
            'partner', 
            'department', 
            'warehouse', 
            'items'
        ])
        // ->where('status', 0)  // إضافة شرط الحالة
        ->get();
    }

   
    
    // تحميل تفاصيل الحركة وتوزيع المنتجات بناءً على الحركة المحددة
    public function loadTransactionDetails()
    {
        // dd($this->selectedTransactionId);

        $this->selectedTransaction = InventoryTransaction::with([
            'items',
            'partner',
            'department',
            'warehouse.storageAreas',
            'warehouse.warehouseLocations',
            'items.inventoryProducts'
        ])->findOrFail($this->selectedTransactionId);
        
            // dd($this->selectedTransactionId);
            
        
    }
    
    public function loadDistributions()
{
    if ($this->selectedTransactionId) {
        $this->distribution = [];

        // تحديث التوزيعات بناءً على تفاصيل الحركة
        $this->selectedTransaction = InventoryTransaction::with([
            'items',
            'items.inventoryTransaction',
            'partner',
            'department',
            'warehouse.storageAreas',
            'warehouse.warehouseLocations',
            'items.inventoryProducts',
            'transactionType'
        ])->findOrFail($this->selectedTransactionId);

        $this->selectedTransaction = InventoryTransaction::with([
            'items',
            'items.inventoryTransaction', // استيراد الحقول من جدول الحركة
            'partner',
            'department',
            'warehouse.storageAreas',
            'warehouse.warehouseLocations',
            'items.inventoryProducts',
            'transactionType'
        ])->findOrFail($this->selectedTransactionId);
        
        foreach ($this->selectedTransaction->items as $item) {
            $distributions = $item->inventoryProducts;
            
            // استخراج secondary_warehouse_id و warehouse_id من العلاقة 'items.inventoryTransaction'
            $secondaryWarehouseName = $item->inventoryTransaction->secondaryWarehouse->name ?? 'اسم غير متاح';
            $warehouseName = $item->inventoryTransaction->warehouse->name ?? 'اسم غير متاح';
            
            // إضافة هذه القيم إلى التوزيعات
            foreach ($distributions as $distribution) {
                $this->distribution[$item->id][] = [
                    'product_id' => $item->product_id ?? null,
                    'storage_area_id' => $distribution->storage_area_id ?? null,
                    'location_id' => $distribution->location_id ?? null,
                    'quantity' => $distribution->quantity ?? null,
                    'secondary_warehouse_id' => $secondaryWarehouseName, // إضافة secondary_warehouse_id
                    'warehouse_id' => $warehouseName, // إضافة warehouse_id
                ];
            }
        }
        
    }
}

    // دالة لحفظ التوزيع بعد التعديل
    public function saveDistribution()
    {
        if (!$this->selectedTransaction) {
            session()->flash('error', 'You must select an inventory transaction before saving.');
            return;
        }
    
        $errors = [];
        foreach ($this->distribution as $itemId => $data) {
            if (empty($data['storage_area_id']) || empty($data['location_id']) || empty($data['quantity'])) {
                $errors[] = "Please fill all fields for item ID: $itemId before saving.";
                continue;
            }
    
            // تحديث أو إنشاء التوزيع في قاعدة البيانات
            InventoryProduct::updateOrCreate(
                ['inventory_transaction_item_id' => $itemId],
                [
                    'product_id' => $data['product_id'],
                    'warehouse_id' => $this->selectedTransaction->warehouse_id,
                    'storage_area_id' => $data['storage_area_id'],
                    'location_id' => $data['location_id'],
                    'quantity' => $data['quantity'],
                    'branch_id' => $this->selectedTransaction->branch_id ?? null,
                    'updated_user' => auth()->id(),
                    'created_user' => $this->selectedTransaction->created_user ?? auth()->id(),
                ]
            );
        }
    
        if (!empty($errors)) {
            session()->flash('error', implode('<br>', $errors));
        } else {
            session()->flash('success', 'Distribution saved successfully.');
        }
    }
    

    public function previousTransaction()
    {
        $currentIndex = collect($this->transactions)
            ->search(function ($transaction) {
                return $transaction->id == $this->selectedTransactionId;
            });
    
        if ($currentIndex > 0) {
            $this->selectedTransactionId = $this->transactions[$currentIndex - 1]->id;
            $this->loadTransactionDetails();
        }
    }
    
    public function nextTransaction()
    {
        $currentIndex = collect($this->transactions)
            ->search(function ($transaction) {
                return $transaction->id == $this->selectedTransactionId;
            });
    
        if ($currentIndex < count($this->transactions) - 1) {
            $this->selectedTransactionId = $this->transactions[$currentIndex + 1]->id;
            $this->loadTransactionDetails();
        }
    }
    


    public function searchTransactions()
    {
        $this->transactions = InventoryTransaction::with([
                'partner',
                'department',
                'warehouse',
                'items'
            ])
            ->where('reference', 'like', '%' . $this->search . '%')
            ->orWhereHas('partner', fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orWhereHas('department', fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orWhereHas('warehouse', fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->get();

        // Automatically set the first transaction after search
        if ($this->transaction->isNotEmpty()) {
            $this->selectedTransactionId = $this->transactions[0]->id;
            $this->loadTransactionDetails();
        }
    }
    // public function updated($propertyName)
    // {
    //     if ($propertyName === 'search') {
    //         $this->searchTransactions();
    //     }
    // }
    
 public function toggleDistribution($transactionId)
{
    // ابحث عن المعاملة في المصفوفة
    $index = collect($this->transactions)->search(fn($transaction) => $transaction['id'] === $transactionId);
    
    if ($index !== false) {
        // تحديث الحالة في قاعدة البيانات
        $transaction = InventoryTransaction::findOrFail($transactionId);
        $transaction->status = $transaction->status == 0 ? 1 : 0;
        $transaction->save();
    
        // تحديث الحالة في المصفوفة مباشرة لتحديث الواجهة
        $this->transactions[$index]['status'] = $transaction->status;
        
        // إشعار Livewire بتحديث الحالة
        // $this->emit('transactionUpdated', $transactionId);
    }
    
    // إعادة تحميل تفاصيل الحركة إذا كانت هي المختارة حاليًا
    if ($this->selectedTransactionId === $transactionId) {
        $this->selectedTransaction = $transaction;
    }
}

    
    
    public function render()
    
    {
    
        // dd($this->selectedTransaction ? $this->selectedTransaction->items : 'No transaction selected');

        return view('livewire.inventory-transaction-distribute', [
            'transactions' => $this->transactions,
            'selectedTransaction' => $this->selectedTransaction,
        ]);
    }
}
