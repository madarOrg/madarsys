<?php

namespace App\Listeners;

use App\Events\InventoryTransactionCreated;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryTransaction\InventoryCalculationService;

class CreateInventoryTransactionListener
{
    protected $inventoryCalculationService;

    public function __construct(InventoryCalculationService $inventoryCalculationService)
    {
        $this->inventoryCalculationService = $inventoryCalculationService;
    }
    //////////////////
    public function handle(InventoryTransactionCreated $event)
    {
        // dd($event);
        $data = $event->data;
        DB::beginTransaction();

        try {
            // إنشاء السجل الرئيسي للحركة المخزنية
            try {
                $transaction = InventoryTransaction::create([
                    'transaction_type_id'  => $data['transaction_type_id'] ?? null,
                    'effect'               => $data['effect'] ?? null,
                    'transaction_date'     => $data['transaction_date'] ?? null,
                    'reference'            => $data['reference'] ?? null,
                    'partner_id'           => $data['partner_id'] ?? null,
                    'department_id'        => $data['department_id'] ?? null,
                    'warehouse_id'         => $data['warehouse_id'] ?? null,
                    'secondary_warehouse_id' => $data['secondary_warehouse_id'] ?? null,
                    'notes'                => $data['notes'] ?? null,
                    'inventory_request_id' => $data['inventory_request_id'] ?? null,
                ]);
            } catch (\Exception $e) {
                // dump("خطأ أثناء إنشاء الحركة المخزنية:", $e->getMessage());
                // session()->flash('error', 'خطأ أثناء إنشاء  الحركة المخزنية:' . $e->getMessage());
                throw new \Exception("خطأ أثناء إنشاء  المخزنية: " . $e->getMessage());
            }
            //   dd($data['transactionItems']); // تحقق إذا كانت مصفوفة `transactionItems` غير فارغة.

            foreach ($data['transactionItems'] as $index => $item) {
                //   dd($item['unit_id'], $item['unit_price'], $item['total']);

                $quantity = $item['quantity'];
                $unitId = $item['unit_id'][$index] ?? null;
                $productUnit = Product::find($item['product_id'])->unit_id;
                $pricePerUnit = $item['unit_price'] ?: ($item['total'] / ($quantity ?: 1));
                $priceTotal = $item['total'] ?: ($quantity * $pricePerUnit);

                // dd($quantity, $unitId, $productUnit, $pricePerUnit, $priceTotal);

                // استدعاء الدالة لمعالجة العملية
                $this->createInventoryMovement($transaction, $data, $item['product_id'], $quantity, $unitId, $pricePerUnit, $priceTotal, $productUnit, $index);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // session()->flash('error', 'خطأ أثناء إنشاء الحركة المخزنية:' . $e->getMessage());

            throw new \Exception($e->getMessage());
        }
    }
    /////////////////create Inventory Movement
    private function createInventoryMovement($transaction, $data, $productId, $quantityInput, $unitId, $pricePerUnit, $priceTotal, $productUnit, $index)
    {
        $transactionType = DB::table('transaction_types')
            ->select('inventory_movement_count', 'effect')
            ->where('id', $data['transaction_type_id'])
            ->first();
    // dd($transactionType);
        if ($transactionType && $transactionType->inventory_movement_count == 2) {
            // إنشاء سجل خروج من المخزن المصدر

            $outQuantity = -$quantityInput;
            $convertedOutQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($outQuantity, $unitId);
            $convertedPrice = $this->inventoryCalculationService->calculateConvertedPrice($pricePerUnit, $unitId);
            // التحقق من توفر الكمية قبل إنشاء الحركة
            if (!$this->isQuantityAvailable($data['warehouse_id'], $productId, $outQuantity)) {
                session()->flash('error', "خطأ: الكمية غير متوفرة في المخزون للمنتج ID: {$productId} في المستودع ID: {$data['warehouse_id']}");
                throw new \Exception("خطأ: الكمية غير متوفرة في المخزون للمنتج ID: {$productId} في المستودع ID: {$data['warehouse_id']}");
            }
            InventoryTransactionItem::create([
                'inventory_transaction_id' => $transaction->id,
                'target_warehouse_id'      => $data['warehouse_id'],
                'unit_id'                  => $unitId,
                'product_id'               => $productId,
                'quantity'                 => $outQuantity,
                'unit_prices'              => -$pricePerUnit,
                'total'                    => -$priceTotal,
                'warehouse_location_id'    => $data['warehouse_locations'][$index] ?? null,
                'converted_quantity'       => $convertedOutQuantity,
                'unit_product_id'          => $productUnit,
                'converted_price'          => $convertedPrice,

            ]);
            // تحديث الكميات في جدول المخزون
            // $this->updateInventoryStock($data['warehouse_id'], $productId, $outQuantity, -$pricePerUnit);

            // إنشاء سجل دخول في المخزن الوجهة
            $inQuantity = abs($quantityInput);
            $convertedInQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($inQuantity, $unitId);
            $convertedPrice = $this->inventoryCalculationService->calculateConvertedPrice($pricePerUnit, $unitId);

            InventoryTransactionItem::create([
                'inventory_transaction_id' => $transaction->id,
                'target_warehouse_id'      => $data['secondary_warehouse_id'],
                'unit_id'                  => $unitId,
                'product_id'               => $productId,
                'quantity'                 => $inQuantity,
                'unit_prices'              => abs($pricePerUnit),
                'total'                    => abs($priceTotal),
                'warehouse_location_id'    => $data['warehouse_locations'][$index] ?? null,
                'converted_quantity'       => $convertedInQuantity,
                'unit_product_id'          => $productUnit,
                'converted_price'          => $convertedPrice,

            ]);
            // تحديث الكميات في جدول المخزون

            // $this->updateInventoryStock($data['secondary_warehouse_id'], $productId, $inQuantity, $pricePerUnit);
        } else if ($transactionType && $transactionType->inventory_movement_count == 1) {
            // إنشاء حركة عادية مثل بيع أو شراء

            if ($transactionType && $transactionType->effect != 0) {
                $effect = $transactionType->effect;
                $quantityInput = $quantityInput * $effect;
                $pricePerUnit = $pricePerUnit * $effect;
                $priceTotal = $priceTotal * $effect;

                // dd('create InventoryS',$quantityInput);
            }
            // التحقق من توفر الكمية قبل إنشاء الحركة
            if (!$this->isQuantityAvailable($data['warehouse_id'], $productId, $quantityInput)) {
                // dump("خطأ أثناء إنشاء تفاصيل الحركة المخزنية:");
                session()->flash('error', "خطأ: الكمية غير متوفرة في المخزون للمنتج ID: {$productId} في المستودع ID: {$data['warehouse_id']}");
                throw new \Exception("خطأ: الكمية غير متوفرة في المخزون للمنتج ID: {$productId} في المستودع ID: {$data['warehouse_id']}");
            }
            $convertedQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($quantityInput, $unitId);
            $convertedPrice = $this->inventoryCalculationService->calculateConvertedPrice($pricePerUnit, $unitId);
            // dd($convertedPrice);

            try {
                InventoryTransactionItem::create([
                    'inventory_transaction_id' => $transaction->id,
                    'target_warehouse_id'      => $data['warehouse_id'],
                    'unit_id'                  => $unitId,
                    'product_id'               => $productId,
                    'quantity'                 => $quantityInput,
                    'unit_prices'              => $pricePerUnit,
                    'total'                    => $priceTotal,
                    'warehouse_location_id'    => $data['warehouse_locations'][$index] ?? null,
                    'converted_quantity'       => $convertedQuantity,
                    'unit_product_id'          => $productUnit,
                    'converted_price'          => $convertedPrice,


                ]);

                // تحديث الكميات في جدول المخزون
            } catch (\Exception $e) {
                // dump("خطأ أثناء إنشاء تفاصيل الحركة المخزنية:", $e->getMessage());
                session()->flash('error', 'خطأ أثناء إنشاء تفاصيل الحركة المخزنية:' . $e->getMessage());
                throw new \Exception("خطأ أثناء إنشاء  تفاصيل الحركة المخزنية: " . $e->getMessage());
            }
            //لا نؤثر على كميات المخزون لان جالة الحركة معلقة
            // $this->updateInventoryStock($data['warehouse_id'], $productId, $convertedQuantity, $pricePerUnit);
        }
    }
    /**
     * دالة للتحقق من توفر كمية معينة من المنتج في مخزن محدد
     *
     * @param int $warehouseId معرف المستودع
     * @param int $productId   معرف المنتج
     * @param int $requestedQuantity الكمية المطلوبة
     *
     * @return bool ترجع true إذا كانت الكمية متوفرة، و false إذا لم تكن كذلك
     */
    private function isQuantityAvailable($warehouseId, $productId, $requestedQuantity)
    {
        // جلب سجل المخزون التجميعي للمنتج في المستودع
        $inventory = Inventory::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();
        //  dd($inventory);
        // إذا لم يكن هناك سجل للمخزون، فهذا يعني أنه لا توجد كميات متوفرة
        if (!$inventory && $requestedQuantity < 0) {
            return false;
        }
        // dd($requestedQuantity);

        // التحقق مما إذا كانت الكمية المتوفرة أكبر من أو تساوي الكمية المطلوبة
        return $inventory->quantity >= $requestedQuantity;
    }


    // تحديث أو إضافة الكمية التراكمية في جدول المخزون
    private function updateInventoryStock($warehouseId, $productId, $quantity, $pricePerUnit)
    {
        // جلب السجل الحالي للمخزون لهذا المنتج في هذا المستودع
        $inventory = Inventory::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();
        if ($inventory) {
            // تحديث الكمية
            $newQuantity = $inventory->quantity + $quantity;

            // التأكد من عدم أن تكون الكمية سالبة (لا يمكن أن يصبح المخزون بالسالب)
            if ($newQuantity < 0) {
                session()->flash('error', 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.' . $e->getMessage());
                throw new \Exception("خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.");
            }

            // تحديث السعر الإجمالي
            $newTotalValue = ($inventory->total_value + ($quantity * $pricePerUnit));

            // تحديث المخزون
            $inventory->update([
                'quantity' => $newQuantity,
                'unit_price' => $newQuantity > 0 ? $newTotalValue / $newQuantity : 0, // تجنب القسمة على صفر
                'total_value' => $newTotalValue,
            ]);
        } else {
            // إذا لم يكن هناك سجل، أنشئ سجلًا جديدًا
            if ($quantity < 0) {
                session()->flash('error', 'خطأ: لا يمكن إخراج منتج غير موجود في المخزون.' . $e->getMessage());
                throw new \Exception("خطأ: لا يمكن إخراج منتج غير موجود في المخزون.");
            }

            Inventory::create([
                'warehouse_id' => $warehouseId,
                'product_id'   => $productId,
                'quantity'     => $quantity,
                'unit_price'   => $pricePerUnit,
                'total_value'  => $quantity * $pricePerUnit,
            ]);
        }
    }
}
