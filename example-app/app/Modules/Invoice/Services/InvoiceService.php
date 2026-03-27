<?php

namespace Modules\Invoice\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Invoice\DTOs\CreateInvoiceDTO;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceItem;
use Modules\Sales\Models\Order;
use Modules\Sales\States\Approved as OrderApproved;

class InvoiceService
{
    public function getAll(array $filters = []): Collection
    {
        return Invoice::with(['order', 'customer', 'items'])
            ->when(! empty($filters['status']), function (Builder $query) use ($filters) {
                $status = strtolower(trim((string) $filters['status']));

                if (in_array($status, ['paid', 'unpaid'], true)) {
                    $query->where('payment_status', $status);
                }
            })
            ->when(! empty($filters['date']), function (Builder $query) use ($filters) {
                $query->whereDate('issued_at', $filters['date']);
            })
            ->when(isset($filters['total']) && $filters['total'] !== '', function (Builder $query) use ($filters) {
                $query->where('total_amount', (float) $filters['total']);
            })
            ->latest()
            ->get();
    }

    public function create(CreateInvoiceDTO $dto, int $userId): Invoice
    {
        return DB::transaction(function () use ($dto, $userId) {
            $order = Order::with('items.product')->findOrFail($dto->order_id);

            if (! $order->state->equals(OrderApproved::class)) {
                throw new \Exception('Cannot create invoice for unapproved order');
            }

            $invoice = Invoice::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'total_amount' => $order->total_amount,
                'issued_at' => now(),
                'due_date' => now()->addDays(14),
                'issued_by' => $userId,
            ]);

            foreach ($order->items as $orderItem) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $orderItem->product_id,
                    'description' => $orderItem->product->name,
                    'quantity' => $orderItem->quantity,
                    'unit_price' => $orderItem->price,
                    'subtotal' => $orderItem->quantity * $orderItem->price,
                ]);
            }

            return $invoice->load('items');
        });
    }
}
