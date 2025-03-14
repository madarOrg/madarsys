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
   
    
    public function mount()
    {
        $this->inventoryTransactionService = new InventoryTransactionService();
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
        ])->get();
    }

   
    public $selectedItemId = null; // المتغير لتحديد العنصر المحدد
    public $showDistributionModal = false; // المتغير الذي يتحكم في عرض المودال
    
    // تحميل تفاصيل الحركة وتوزيع المنتجات بناءً على الحركة المحددة
    public function loadTransactionDetails()
    {
        if ($this->selectedTransactionId) {
            $this->selectedTransaction = InventoryTransaction::with([
                'items',
                'partner',
                'department',
                'warehouse.storageAreas',
                'warehouse.warehouseLocations',
                'items.inventoryProducts'
            ])->findOrFail($this->selectedTransactionId);
            
        }
    }
    
    public function loadDistributions()
{
    if ($this->selectedTransactionId) {
        $this->distribution = [];

        // تحديث التوزيعات بناءً على تفاصيل الحركة
        $this->selectedTransaction = InventoryTransaction::with([
            'items',
            'partner',
            'department',
            'warehouse.storageAreas',
            'warehouse.warehouseLocations',
            'items.inventoryProducts'
        ])->findOrFail($this->selectedTransactionId);

        foreach ($this->selectedTransaction->items as $item) {
            $distributions = $item->inventoryProducts;
        
            foreach ($distributions as $distribution) {
                $this->distribution[$item->id][] = [
                    'product_id' => $item->product_id ?? null,
                    'storage_area_id' => $distribution->storage_area_id ?? null,
                    'location_id' => $distribution->location_id ?? null,
                    'quantity' => $distribution->quantity ?? null,
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
    
// إضافة وظائف التنقل
public function previousProduct()
{
    // تحديد مكان الحركة الحالية في قائمة الحركات
    $currentIndex = collect($this->transactions)
        ->search(fn($transaction) => $transaction->id == $this->selectedTransactionId);
    
    // الانتقال إلى الحركة السابقة إذا كانت موجودة
    if ($currentIndex > 0) {
        $this->selectedTransactionId = $this->transactions[$currentIndex - 1]->id;
        $this->loadDistributions(); // فقط تحديث التوزيعات
    }
}

public function nextProduct()
{
    // تحديد مكان الحركة الحالية في قائمة الحركات
    $currentIndex = collect($this->transactions)
        ->search(fn($transaction) => $transaction->id == $this->selectedTransactionId);
    
    // الانتقال إلى الحركة التالية إذا كانت موجودة
    if ($currentIndex < count($this->transactions) - 1) {
        $this->selectedTransactionId = $this->transactions[$currentIndex + 1]->id;
        $this->loadDistributions(); // فقط تحديث التوزيعات
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
        $this->loadTransactionDetails(); // تحديث تفاصيل الحركة
        $this->loadDistributions(); // تحديث التوزيعات
        $this->emit('focusTransactionDetails'); // إرسال الحدث إلى JavaScript
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
        $this->loadTransactionDetails(); // تحديث تفاصيل الحركة
        $this->loadDistributions(); // تحديث التوزيعات
        $this->emit('focusTransactionDetails'); // إرسال الحدث إلى JavaScript
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
        if ($this->transactions->isNotEmpty()) {
            $this->selectedTransactionId = $this->transactions[0]->id;
            $this->loadTransactionDetails();
        }
    }

   

    public function render()
    {
        return view('livewire.inventory-transaction-distribute', [
            'transactions' => $this->transactions,
            'selectedTransaction' => $this->selectedTransaction,
        ]);
    }
}
