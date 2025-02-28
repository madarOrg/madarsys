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
    WarehouseLocation
};
use App\Services\InventoryTransaction\InventoryValidationService;
use Carbon\Carbon;

class InventoryTransactionLivewire extends Component
{
    protected $inventoryValidationService;

    public $transactionTypes, $partners, $departments, $warehouses, $products, $warehouseLocations;
    public $transaction_type_id, $transaction_date, $effect, $reference, $partner_id, $department_id, $warehouse_id, $secondary_warehouse_id, $notes;
    public $transactionItems = [];

    protected $inventoryTransactionService;

    public function __construct()
    {
        $this->inventoryTransactionService = new InventoryTransactionService();
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
            $transaction = $this->inventoryTransactionService->createTransaction($validatedData);
            $this->reset(['transaction_type_id', 'transaction_date', 'effect', 'reference', 'partner_id', 'department_id', 'warehouse_id', 'secondary_warehouse_id', 'notes', 'transactionItems']);

    // إعادة ضبط التاريخ إلى الآن
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
    public function updateUnits($index)
{
    // جلب معرف المنتج من transactionItems
    $productId = $this->transactionItems[$index]['product_id'];

    // إذا لم يتم تحديد منتج، يتم تفريغ قائمة الوحدات وتعيين unit_id إلى null
    if (!$productId) {
        $this->transactionItems[$index]['units'] = [];
        $this->transactionItems[$index]['unit_id'] = null;
        return;
    }

    // جلب المنتج مع العلاقة للوحدة الأساسية فقط
    $product = Product::with('unit')->find($productId);

    // التأكد من وجود المنتج والوحدة الأساسية
    if (!$product || !$product->unit) {
        $this->transactionItems[$index]['units'] = [];
        $this->transactionItems[$index]['unit_id'] = null;
        return;
    }

    // بدء تجميع الوحدات، نبدأ بالوحدة الأساسية
    $units = [];
    $units[] = $product->unit;

    // الحصول على جميع الوحدات الفرعية (الأبناء)
    $this->getAllChildren($product->unit, $units);

    // الحصول على جميع الوحدات العليا (الآباء)
    $this->getAllParents($product->unit, $units);

    // تجنب التكرار باستخدام unique على أساس الـ id
    $units = collect($units)->unique('id')->values();

    // تحويل البيانات إلى مصفوفة تحتوي على id واسم الوحدة لتستخدمها الواجهة
    $this->transactionItems[$index]['units'] = $units->map(function ($unit) {
        return [
            'id'   => $unit->id,
            'name' => $unit->name,
        ];
    })->toArray();

    // تعيين الوحدة الافتراضية (الوحدة الأساسية) كـ unit_id
    $this->transactionItems[$index]['unit_id'] = $product->unit->id;
    $this->transactionItems = array_values($this->transactionItems);
}

    /**
     * دالة للحصول على جميع الوحدات الفرعية (الأبناء) بشكل متداخل
     */
    private function getAllChildren($unit, &$units)
    {
        if (isset($unit->children) && $unit->children->count()) {
            foreach ($unit->children as $child) {
                $units[] = $child;
                $this->getAllChildren($child, $units);
            }
        }
    }

    /**
     * دالة للحصول على جميع الوحدات العليا (الآباء) بشكل متداخل
     */
    private function getAllParents($unit, &$units)
    {
        if ($unit->parent) {
            $units[] = $unit->parent;
            $this->getAllParents($unit->parent, $units);
        }
    }



    public function render()
    {
        return view('livewire.inventory-transaction');
        
    }
}
