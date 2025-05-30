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
        $data = $event->data;
        // dd(gettype($data));
        //   dd($event);
        // التحقق إذا كانت المصفوفة "products" فارغة

        if (empty($data['products']) || count($data['products']) == 0) {
            // إذا لم تكن هناك منتجات، نضيف منتج افتراضي
            $product = \App\Models\Product::first();
            if ($product) {
                // إنشاء مصفوفات المنتجات إذا لم تكن موجودة
                $data['products'] = [$product->id];
                $data['units'] = [$product->unit_id ?? 1];
                $data['quantities'] = [1];
                $data['unit_prices'] = [100];
                $data['totals'] = [100];
                $data['warehouse_locations'] = [null];
            } else {
                session()->flash('error', 'لا يمكن إنشاء حركة مخزنية فارغة ولا يوجد منتج افتراضي.');
                throw new \Exception('لا يمكن إنشاء حركة مخزنية فارغة ولا يوجد منتج افتراضي.');
            }
        }

        DB::beginTransaction();
        // dd('b',$data['transaction_type_id']);

        $transactionType = DB::table('transaction_types')
            ->select('inventory_movement_count', 'effect')
            ->where('id', $data['transaction_type_id'])
            ->first();
        //اذا كانت الحركة لا تؤثؤ على المخزون تكون الحالة فعالة 2 , مالم تكون الحالة معلقة حتى يتم التوزيع
        if ($transactionType->inventory_movement_count == 0) {
            $inventoryStatus = 2;
        } else {
            $inventoryStatus = 0;
        };

        // try {
        // إنشاء السجل الرئيسي للحركة المخزنية
        $transaction = InventoryTransaction::create([
            'transaction_type_id'     => $data['transaction_type_id'] ?? null,
            'sub_type_id         '     => $data['sub_type_id'] ?? null,
            'effect'                  => $data['effect'] ?? null,
            'transaction_date'        => date('Y-m-d H:i:s', strtotime($data['transaction_date'])),
            'reference'               => $data['reference'] ?? null,
            'partner_id'              => $data['partner_id'] ?? null,
            'department_id'           => $data['department_id'] ?? null,
            'warehouse_id'            => $data['warehouse_id'] ?? null,
            'secondary_warehouse_id'  => $data['secondary_warehouse_id'] ?? null,
            'notes'                   => $data['notes'] ?? null,
            'inventory_request_id'    => $data['inventory_request_id'] ?? null,
            'status'                  => $data['status'] ?? $inventoryStatus,
        ]);
        // dd($transaction);
        // تحقق من المصفوفات المرتبطة
        $products = $data['products'];
        $units = $data['units'];
        $quantities = $data['quantities'];
        $unitPrices = $data['unit_prices'];
        $totals = $data['totals'];
        $locations = $data['warehouse_locations'];
        // dd($transaction, $data);

        foreach ($products as $index => $product) {
            // الحصول على القيم الخاصة بكل منتج
            $quantity = $quantities[$index] ?? 0;
            $unitId = $units[$index] ?? null;
            $pricePerUnit = $unitPrices[$index] ?? 0;
            $priceTotal = $totals[$index] ?? 0;
            $location = $locations[$index] ?? null;
            // استدعاء دالة معالجة الحركة المخزنية
            $this->createInventoryMovement($transaction, $data, $product, $quantity, $unitId, $pricePerUnit, $priceTotal, $location, $index, $transactionType);
        }
        // dd($products);
        DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     // التعامل مع الأخطاء
        //     throw new \Exception($e->getMessage());
        // }
    }

    /////////////////create Inventory Movement
    private function createInventoryMovement($transaction, $data, $productId, $quantityInput, $unitId, $pricePerUnit, $priceTotal, $location, $index, $transactionType)
    {

        // dd($unitId);
        $convertedQuantity = 0;

        // جلب المنتج للحصول على وحدة المنتج الأساسية
        $product = Product::findOrFail($productId);
        // dd($product);

        $baseUnitId = $product->unit_id;
        // dd($baseUnitId);

        $priceTotal = $quantityInput * $pricePerUnit;
        //  dd($transactionType->inventory_movement_count );

        //transactionType->inventory_movement_count == 2 يعني ان الحركة تسجل حركة ادخال و اخراج
        if ($transactionType && $transactionType->inventory_movement_count == 2) {
            // إنشاء سجل خروج من المخزن المصدر

            $outQuantity = -$quantityInput;
            if ($unitId) {
                $convertedOutQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($outQuantity, $unitId, $baseUnitId);
            }
            // $convertedPrice = $this->inventoryCalculationService->calculateConvertedPrice($pricePerUnit, $unitId,$baseUnitId);

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
                'unit_product_id'          => $baseUnitId,
                'converted_price'          => -$priceTotal,
                'source_warehouse_id'      => $data['source_warehouse_id'][$index] ?? $data['secondary_warehouse_id'],
                'production_date' => $data['production_date'][$index] ?? null,
                'expiration_date' => $data['expiration_date'][$index] ?? null,
                'status' => $data['status'][$index] ?? 0,
                'result' => $data['result'][$index] ?? 0,
                'expected_audit_quantity' => $data['expected_audit_quantity'][$index] ?? null,
                'batch_number' => $data['batch_number'][$index] ?? null,


            ]);
            // تحديث الكميات في جدول المخزون
            // $this->updateInventoryStock($data['warehouse_id'], $productId, $outQuantity, -$pricePerUnit);

            // إنشاء سجل دخول في المخزن الوجهة
            $inQuantity = abs($quantityInput);

            if ($unitId) {
                $convertedInQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($inQuantity, $unitId, $baseUnitId);
            }
            // $convertedPrice = $this->inventoryCalculationService->calculateConvertedPrice($inQuantity, $unitId,$baseUnitId);

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
                'unit_product_id'          => $baseUnitId,
                'converted_price'          => abs($priceTotal),
                'source_warehouse_id'      => $data['warehouse_id'][$index] ?? $data['warehouse_id'],
                'production_date' => $data['production_date'][$index] ?? null,
                'expiration_date' => $data['expiration_date'][$index] ?? null,
                'status' => $data['status'][$index] ?? 0,
                'result' => $data['result'][$index] ?? 0,
                'expected_audit_quantity' => $data['expected_audit_quantity'][$index] ?? null,
                'batch_number' => $data['batch_number'][$index] ?? null,

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
            if ($quantityInput < 0) {
                // التحقق من توفر الكمية قبل إنشاء الحركة
                
                if (!$this->isQuantityAvailable($data['warehouse_id'], $productId, $quantityInput)) {
                    // dd("خطأ أثناء إنشاء تفاصيل الحركة المخزنية:");
                    session()->flash('error', "خطأ: الكمية غير متوفرة في المخزون للمنتج ID: {$productId} في المستودع ID: {$data['warehouse_id']}");
                    throw new \Exception("خطأ: الكمية غير متوفرة في المخزون للمنتج ID: {$productId} في المستودع ID: {$data['warehouse_id']}");
                }
            }

            if ($unitId) {

                $convertedQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($quantityInput, $unitId, $baseUnitId);
            }

            // $convertedPrice = $this->inventoryCalculationService->calculateConvertedPrice($quantityInput, $unitId,$baseUnitId);

            // dd($convertedPrice);

            // try {
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
                    'unit_product_id'          => $baseUnitId,
                    'converted_price'          => $priceTotal,
                    'source_warehouse_id'      => $data['source_warehouse_id'][$index] ?? $data['secondary_warehouse_id'],
                    'production_date'          => $data['production_date'][$index] ?? null,
                    'expiration_date'          => $data['expiration_date'][$index] ?? null,
                    'status' => $data['status'][$index] ?? 0,
                    'result' => $data['result'][$index] ?? 0,
                    'expected_audit_quantity' => $data['expected_audit_quantity'][$index] ?? null,
                    'batch_number' => $data['batch_number'][$index] ?? null,
                ]);
                // dd($data['warehouse_locations'][$index] );
                // تحديث الكميات في جدول المخزون
            // } catch (\Exception $e) {
            //     // dump("خطأ أثناء إنشاء تفاصيل الحركة المخزنية:", $e->getMessage());
            //     session()->flash('error', 'خطأ أثناء إنشاء تفاصيل الحركة المخزنية:' . $e->getMessage());
            //     throw new \Exception("خطأ أثناء إنشاء  تفاصيل الحركة المخزنية: " . $e->getMessage());
            // }
            //لا نؤثر على كميات المخزون لان جالة الحركة معلقة
        } else if ($transactionType && $transactionType->inventory_movement_count == 0) {
            // إنشاء حركة الجردء
            $batch_number = 0;

            $effect = 0;
            $pricePerUnit = $pricePerUnit;
            $priceTotal = $priceTotal;
            //   dd( $data['batchs'][$index]);
            // dd(($data));


            try {
                InventoryTransactionItem::create([
                    'inventory_transaction_id' => $transaction->id,
                    'target_warehouse_id'      => $data['warehouse_id'],
                    'unit_id'                  => $unitId,
                    'product_id'               => $productId,
                    'quantity'                 => 0,
                    'unit_prices'              => $pricePerUnit,
                    'total'                    => $priceTotal,
                    'warehouse_location_id'    => $data['warehouse_locations'][$index] ?? null,
                    'converted_quantity'       => $convertedQuantity,
                    'unit_product_id'          => $baseUnitId,
                    'converted_price'          => $priceTotal,
                    'source_warehouse_id'      => $data['source_warehouse_id'][$index] ?? $data['secondary_warehouse_id'],
                    'production_date'          => $data['production_date'][$index] ?? null,
                    'expiration_date'          => $data['expiration_date'][$index] ?? null,
                    'status' => $data['status'][$index] ?? 2,
                    'result' => $data['result'][$index] ?? 0,
                    'expected_audit_quantity' => $quantityInput,
                    'batch_number' => $data['batchs'][$index] ?? null,
                ]);
                // dd($data['warehouse_locations'][$index] );
                // تحديث الكميات في جدول المخزون
            } catch (\Exception $e) {
                // dump("خطأ أثناء إنشاء تفاصيل الحركة المخزنية:", $e->getMessage());
                session()->flash('error', 'خطأ أثناء إنشاء تفاصيل الحركة المخزنية:' . $e->getMessage());
                throw new \Exception("خطأ أثناء إنشاء  تفاصيل الحركة المخزنية: " . $e->getMessage());
            }
            //لا نؤثر على كميات المخزون لان جالة الحركة معلقة
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
        // dd($inventory);
        // إذا لم يكن هناك سجل للمخزون، فهذا يعني أنه لا توجد كميات متوفرة
        if (!$inventory && $requestedQuantity < 0) {
            return false;
        }
        // dd($requestedQuantity);

        // التحقق مما إذا كانت الكمية المتوفرة أكبر من أو تساوي الكمية المطلوبة
        return $inventory->quantity >= $requestedQuantity;
    }
}
