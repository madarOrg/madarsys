<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddInvoiceFromOrdersMenuActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // الحصول على معرف وحدة الفواتير
        $invoiceModuleId = DB::table('modules')->where('key', 'invoices.index')->first()->id ?? null;
        
        if ($invoiceModuleId) {
            // إضافة إجراءات جديدة لوحدة الفواتير
            $actions = [
                [
                    'module_id' => $invoiceModuleId,
                    'name' => 'فواتير من أوامر الشراء',
                    'route' => 'invoices.purchase-orders',
                    'icon' => 'fas fa-file-invoice-dollar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'module_id' => $invoiceModuleId,
                    'name' => 'فواتير من أوامر الصرف',
                    'route' => 'invoices.sales-orders',
                    'icon' => 'fas fa-file-invoice',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            
            // إضافة الإجراءات إلى جدول الإجراءات
            DB::table('actions')->insert($actions);
            
            // الحصول على معرفات الإجراءات المضافة
            $purchaseOrdersActionId = DB::table('actions')
                ->where('route', 'invoices.purchase-orders')
                ->first()->id ?? null;
                
            $salesOrdersActionId = DB::table('actions')
                ->where('route', 'invoices.sales-orders')
                ->first()->id ?? null;
            
            // إضافة أذونات للإجراءات
            if ($purchaseOrdersActionId) {
                DB::table('permissions')->insert([
                    'action_id' => $purchaseOrdersActionId,
                    'permission_key' => 'invoices.purchase-orders',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            if ($salesOrdersActionId) {
                DB::table('permissions')->insert([
                    'action_id' => $salesOrdersActionId,
                    'permission_key' => 'invoices.sales-orders',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // إضافة الأذونات لجميع الأدوار
            $roleIds = DB::table('roles')->pluck('id')->toArray();
            $permissionIds = DB::table('permissions')
                ->whereIn('permission_key', ['invoices.purchase-orders', 'invoices.sales-orders'])
                ->pluck('id')
                ->toArray();
            
            foreach ($roleIds as $roleId) {
                foreach ($permissionIds as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // حذف الأذونات من جدول role_permissions
        $permissionIds = DB::table('permissions')
            ->whereIn('permission_key', ['invoices.purchase-orders', 'invoices.sales-orders'])
            ->pluck('id')
            ->toArray();
        
        DB::table('role_permissions')->whereIn('permission_id', $permissionIds)->delete();
        
        // حذف الأذونات
        DB::table('permissions')
            ->whereIn('permission_key', ['invoices.purchase-orders', 'invoices.sales-orders'])
            ->delete();
        
        // حذف الإجراءات
        DB::table('actions')
            ->whereIn('route', ['invoices.purchase-orders', 'invoices.sales-orders'])
            ->delete();
    }
}
