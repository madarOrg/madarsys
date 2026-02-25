<?php
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$startDate = Carbon::now()->subDays(30);

// استهلاك المنتجات خلال آخر 30 يوم
$usageData = DB::table('inventory_transaction_items as items')
    ->join('inventory_transactions as transactions', 'items.inventory_transaction_id', '=', 'transactions.id')
    ->whereIn('transactions.transaction_type_id',7)
    ->whereDate('transactions.transaction_date', '>=', $startDate)
    ->groupBy('items.product_id')
    ->select(
        'items.product_id',
        DB::raw('SUM(items.converted_quantity) as total_quantity')
    )
    ->get();

    $forecast = [];

foreach ($usageData as $item) {
    $avgDaily = $item->total_quantity / 30;

    $product = \App\Models\Product::find($item->product_id);
    $currentStock = $product->current_quantity ?? 0;

    $daysLeft = $avgDaily > 0 ? round($currentStock / $avgDaily, 1) : '∞';

    $forecast[] = [
        'product_name' => $product->name,
        'average_daily_usage' => round($avgDaily, 2),
        'current_quantity' => $currentStock,
        'estimated_days_left' => $daysLeft,
    ];
}
