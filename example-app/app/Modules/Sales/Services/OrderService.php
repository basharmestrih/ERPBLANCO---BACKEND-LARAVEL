<?php

namespace Modules\Sales\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Sales\DTOs\CreateOrderDTO;
use Modules\Sales\Events\OrderApproved;
use Modules\Sales\Models\Order;
use Modules\Sales\Models\OrderItem;
use Modules\Sales\States\Approved;
use Modules\Sales\States\Cancelled;
use Modules\Sales\States\Pending;

class OrderService
{
    public function getAll(array $filters = [])
    {
        return Order::with(['items.product', 'customer', 'creator', 'approver'])
            ->when(! empty($filters['status']), function (Builder $query) use ($filters) {
                $state = $this->resolveOrderState($filters['status']);

                if ($state !== null) {
                    $query->where('state', $state);
                }
            })
            ->when(! empty($filters['date']), function (Builder $query) use ($filters) {
                $query->whereDate('created_at', $filters['date']);
            })
            ->when(isset($filters['created_by']) && $filters['created_by'] !== '', function (Builder $query) use ($filters) {
                $createdBy = $filters['created_by'];

                if (is_numeric($createdBy)) {
                    $query->where('created_by', (int) $createdBy);

                    return;
                }

                $query->whereHas('creator', function (Builder $creatorQuery) use ($createdBy) {
                    $creatorQuery->where('name', 'like', '%' . trim((string) $createdBy) . '%');
                });
            })
            ->when(! empty($filters['low_quantity']), function (Builder $query) use ($filters) {
                $query->whereRaw(
                    '(select coalesce(sum(order_items.quantity), 0) from order_items where order_items.order_id = orders.id) >= ?',
                    [(int) $filters['low_quantity']]
                );
            })
            ->when(! empty($filters['high_quantity']), function (Builder $query) use ($filters) {
                $query->whereRaw(
                    '(select coalesce(sum(order_items.quantity), 0) from order_items where order_items.order_id = orders.id) <= ?',
                    [(int) $filters['high_quantity']]
                );
            })
            ->latest()
            ->get();
    }

    public function getByCustomer(int $customerId)
{
    return Order::with(['items.product', 'creator', 'approver'])
        ->where('customer_id', $customerId)
        ->get();
}

    public function create(CreateOrderDTO $dto, int $userId): Order
    {
        return DB::transaction(function () use ($dto, $userId) {

            $order = Order::create([
                'customer_id' => $dto->customer_id,
                'created_by' => $userId,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($dto->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);

                //2.check quantity
                if ($product->total_quantity < $item->quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }
                //3.updating total
                $total += $product->price * $item->quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                ]);
            }

            $order->update(['total_amount' => $total]);

            return $order;
        });
    }

    public function approve(Order $order, int $adminId): Order
    {
        return DB::transaction(function () use ($order, $adminId) {

           $order->state->transitionTo(Approved::class);
            $order->update([
                'approved_by' => $adminId,

            ]);
            event(new OrderApproved($order, $adminId));

            return $order;
        });
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }

    private function resolveOrderState(string $status): ?string
    {
        return match (strtolower(trim($status))) {
            'pending', 'modules\\sales\\states\\pending' => Pending::class,
            'approved', 'modules\\sales\\states\\approved' => Approved::class,
            'cancelled', 'canceled', 'modules\\sales\\states\\cancelled' => Cancelled::class,
            default => null,
        };
    }

}
