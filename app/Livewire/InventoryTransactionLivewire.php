<?php

namespace App\Livewire;


use Livewire\Component;
use App\Services\InventoryTransaction\InventoryTransactionService;
use App\Models\{
    TransactionType,
    Partner,
    Department,
    Warehouse,
    Product,
    WarehouseLocation,

    RoleWarehouse,
    RoleUser,
    User
};
use App\Services\InventoryTransaction\InventoryValidationService;
use Carbon\Carbon;
use App\Services\UnitService;
use App\Services\NotificationService;

class InventoryTransactionLivewire extends Component
{
    protected $inventoryValidationService;
    protected $unitService;
    protected $notificationService;



    public $units = [];

    public $transactionTypes, $partners, $departments, $warehouses, $products, $warehouseLocations;
    public $transaction_type_id, $transaction_date, $effect, $reference, $partner_id, $department_id, $warehouse_id, $secondary_warehouse_id, $notes;
    public $transactionItems = [];

    protected $inventoryTransactionService;

    public function __construct()
    {
        $this->inventoryTransactionService = new InventoryTransactionService();
        $this->unitService = new UnitService;
        $this->notificationService = new NotificationService(); // استدعاء خدمة الإشعارات

    }

    public function mount()
    {

        $this->transactionTypes = TransactionType::all();
        $this->partners = Partner::all();
        $this->departments = Department::all();
        $this->warehouses = Warehouse::all();
        $this->products = Product::all();
        $this->warehouseLocations = WarehouseLocation::all();
        $this->transaction_date = Carbon::now()->format('Y-m-d\TH:i');
        $this->effect = 1;  // Default effect value

    }
    public function updateUnits($index)
    {
        if (!$this->unitService) {
            throw new \Exception("UnitService is not initialized.");
        }

        $productId = $this->transactionItems[$index]['product_id'] ?? null;

        if ($productId) {
            $this->transactionItems[$index]['units'] = $this->unitService->updateUnits($productId);
        }
    }

    public function addProductRow()
    {
        // إضافة صف جديد إلى مصفوفة الـ transactionItems
        $this->transactionItems[] = [
            'product_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'warehouse_location_id' => '',
            'units' => [],
            'unit_id' => null
        ];
    }

    public function removeProductRow($index)
    {
        // إزالة صف منتج من المصفوفة
        unset($this->transactionItems[$index]);
        $this->transactionItems = array_values($this->transactionItems);
    }

    public function validateData()
    {
        $validatedData = $this->validate([
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'transaction_date' => 'required|date|date_format:Y-m-d\TH:i',
            'effect' => 'integer',
            'reference' => 'required|string|max:255',
            'partner_id' => 'required|exists:partners,id',
            'department_id' => 'nullable|exists:departments,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'secondary_warehouse_id' => 'nullable|exists:warehouses,id',
            'notes' => 'nullable|string',

            // التحقق من صحة جميع عناصر transactionItems دفعة واحدة
            'transactionItems' => 'required|array|min:1',
            'transactionItems.*.product_id' => 'required|exists:products,id',
            'transactionItems.*.quantity' => 'required|numeric|min:0.01',
            'transactionItems.*.unit_price' => 'required|numeric|min:0',
            'transactionItems.*.warehouse_location_id' => 'nullable|exists:warehouse_locations,id',
        ]);


        return $validatedData;
    }

    public function save()
    {
        // dump($this->transactionItems); // تحقق مما إذا كانت `transactionItems` تحتوي على بيانات قبل التحقق

        // استخدم دالة التحقق للتحقق من صحة البيانات أولًا
        $validatedData = $this->validateData();
        try {
            // دمج البيانات المعتمدة مع العناصر
            $validatedData['transactionItems'] = $this->transactionItems;
            // استدعاء خدمة المعاملات المخزنية لحفظ البيانات
            $transaction  = $this->inventoryTransactionService->createTransaction($validatedData);
            // dd('in:',$validatedData['warehouse_id']);
            // إرسال تنبيه للمستخدمين المرتبطين بالمستودع
            // // التحقق من قيمة المعاملة
            // if ($validatedData) {
            //     // إرسال تنبيه للمستخدمين المرتبطين بالمستودع
            //     $this->sendWarehouseUsersNotification($validatedData['warehouse_id']);
            // }
            // استرجاع اسم المستودع
            $warehouse = Warehouse::find($validatedData['warehouse_id']);
            $warehouseName = $warehouse ? $warehouse->name : 'المستودع غير معروف';
            $message = "تم حفظ عملية جديدة في المستودع: {$warehouseName}";
            // dd($message);
            // إرسال الإشعار عبر الخدمة مع اسم المستودع
            $this->notificationService->sendWarehouseUsersNotification(
                $validatedData['warehouse_id'],
                $message, // الرسالة مع اسم المستودع
                'restocking', // type
                1, // priority
                null, // productId
                null, // inventoryRequestId
                $validatedData['department_id'] ?? null // pass department_id here
            );



            // إعادة ضبط التاريخ إلى الآن
            $this->reset(['transaction_type_id', 'transaction_date', 'effect', 'reference', 'partner_id', 'department_id', 'warehouse_id', 'secondary_warehouse_id', 'notes', 'transactionItems']);
            $this->transaction_date = Carbon::now()->format('Y-m-d\TH:i');

            session()->flash('message', 'تم حفظ العملية بنجاح!');
        } catch (\Exception $e) {

            // session()->flash('error', 'حدث خطأ أثناء حفظ العملية: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }



    public function submit()
    {
        // استدعاء دالة الحفظ
        try {
            $this->save();
            // إرسال حدث لعرض رسالة نجاح
            $this->dispatch('alert', type: 'success', message: 'تم حفظ العملية بنجاح!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: $e->getMessage());
            // throw new \Exception("خطأ أثناء إنشاء العملية: " . $e->getMessage());

        }
    }
    public function updateEffect()
    {
        $type = TransactionType::find($this->transaction_type_id);
        $this->effect = $type ? $type->effect : null;
    }

    public function calculateTotal($index)
    {
        // تحويل الكمية وسعر الوحدة إلى أرقام
        $quantity = floatval($this->transactionItems[$index]['quantity']);
        $unitPrice = floatval($this->transactionItems[$index]['unit_price']);

        // حساب الإجمالي
        $total = $quantity * $unitPrice;

        // تحديث الإجمالي في مصفوفة transactionItems
        $this->transactionItems[$index]['total'] = $total;
    }





    public function render()
    {
        return view('livewire.inventory-transaction');
    }
}
