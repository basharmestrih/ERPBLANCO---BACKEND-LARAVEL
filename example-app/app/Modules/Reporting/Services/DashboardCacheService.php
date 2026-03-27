<?php

namespace Modules\Reporting\Services;

use Modules\Invoice\Models\Invoice;
use Modules\Invoice\States\Paid;
use Modules\Invoice\States\Refunded;
use Modules\ProductWarehouse\Models\ProductWarehouse;
use Modules\Reporting\Models\DashboardCache;
use Modules\Sales\Models\Order;

class DashboardCacheService
{
    private const CACHE_KEY = 'default';
    private const FINAL_INVOICE_STATES = [
        Paid::class,
        Refunded::class,
    ];

    public function refresh(): DashboardCache
    {
        $totalOrders = Order::count();
        $totalOrdersCost = Order::sum('total_amount');
        $pendingInvoices = Invoice::whereNotIn('state', self::FINAL_INVOICE_STATES)->count();

        $lowInventory = ProductWarehouse::select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_quantity')
            ->first();

        $lowInventoryPayload = null;

        if ($lowInventory !== null) {
            $lowInventoryPayload = [
                'product_id' => $lowInventory->product_id,
                'product_name' => $lowInventory->product?->name,
                'quantity' => (int) $lowInventory->total_quantity,
            ];
        }

        return DashboardCache::updateOrCreate(
            ['key' => self::CACHE_KEY],
            [
                'total_orders' => $totalOrders,
                'total_orders_cost' => $totalOrdersCost,
                'pending_invoices' => $pendingInvoices,
                'low_inventory' => $lowInventoryPayload,
                'calculated_at' => now(),
            ]
        );
    }

    public function latest(): ?DashboardCache
    {
        return DashboardCache::where('key', self::CACHE_KEY)
            ->orderByDesc('calculated_at')
            ->first();
    }
}
