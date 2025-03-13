<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\InventoryTransaction\InventoryTransactionService;
use App\Models\InventoryTransaction;

class InventoryTransactionListLivewire extends Component
{
    protected $inventoryTransactionService;
    public $transactions = [];
    public $selectedTransactionId = null;
    public $selectedTransaction = null;
    public $search = ''; // تعريف خاصية البحث

    public function __construct()
    {
        $this->inventoryTransactionService = new InventoryTransactionService();
    }
    // استخدام mount لتقبل المعاملات
    public function mount($transactions)
    {
    // dd($transactions);
        $this->transactions = $transactions;

        // عند تحميل أول عملية، قم بتعيين أول Transaction
        if (!empty($this->transactions)) {
            $this->selectedTransactionId = $this->transactions[0]['id'];
            $this->loadTransactionDetails();
        }
    }

    public function loadTransactions()
    {
        $this->transactions = $this->inventoryTransactionService->getAllTransactions();
        // dd($this->transactions instanceof \Illuminate\Support\Collection);

    }

    public function loadTransactionDetails()
    {
        if ($this->selectedTransactionId) {
            // dd($this->selectedTransactionId);
            $this->selectedTransaction = InventoryTransaction::with(['items', 'partner', 'department'])->where('id', $this->selectedTransactionId)->firstOrFail();

            // $this->selectedTransaction = InventoryTransaction::with('items')->where('id', $this->selectedTransactionId)->firstOrFail();
            // $this->transactions = InventoryTransaction::with('items')->get();

            
            //  @dump($this->selectedTransaction);
        }
    }

    public function updatedSelectedTransactionId()
    {
        // dd($this->selectedTransactionId); // هل يتم تحديثه عند تغيير العملية؟
        $this->loadTransactionDetails();
    }


    public function previousTransaction()
    {
        $currentIndex = collect($this->transactions)->search(fn($transaction) => $transaction['id'] == $this->selectedTransactionId);

        if ($currentIndex > 0) {
            $this->selectedTransactionId = $this->transactions[$currentIndex - 1]['id'];
            $this->loadTransactionDetails();
        }
    }

    public function nextTransaction()
    {
        $currentIndex = collect($this->transactions)->search(fn($transaction) => $transaction['id'] == $this->selectedTransactionId);

        if ($currentIndex < count($this->transactions) - 1) {
            $this->selectedTransactionId = $this->transactions[$currentIndex + 1]['id'];
            $this->loadTransactionDetails();
        }
    }

    public function searchTransactions()
    {
        $this->transactions = InventoryTransaction::with(['partner', 'department', 'warehouse', 'items'])
            ->where('reference', 'like', '%' . $this->search . '%')
            ->orWhereHas('partner', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('department', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('warehouse', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();
    }
    


    public function render()
    {
        return view('livewire.inventory-transaction-list', [
            'transactions' => $this->transactions,
            'selectedTransaction' => $this->selectedTransaction,
        ]);
    }

//     public function render()
// {
//     $transactions = InventoryTransaction::with(['inventoryItems.inventoryProducts.location', 'inventoryItems.inventoryProducts.storageArea'])
//         ->latest()
//         ->get();

//     return view('inventory-review.index', compact('transactions'));
// }

}
