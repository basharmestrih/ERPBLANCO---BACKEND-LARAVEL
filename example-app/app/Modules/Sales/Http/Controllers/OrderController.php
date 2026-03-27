<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Sales\DTOs\CreateOrderDTO;
use Modules\Sales\DTOs\CreateOrderItemDTO;
use Modules\Sales\Models\Order;
use Modules\Sales\Services\OrderService;
use Modules\Sales\States\Approved;
use Modules\Sales\Http\Requests\StoreOrderRequest;
use Modules\Sales\Http\Requests\ApproveOrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    // LIST ORDERS
    public function index(Request $request, OrderService $orderService)
    {
        //$this->authorize('index', Order::class);

        $filters = $request->validate([
            'status' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'created_by' => ['nullable'],
            'low_quantity' => ['nullable', 'integer', 'min:1'],
            'high_quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        return OrderResource::collection($orderService->getAll($filters));
    }


    // Get orders for logged-in customer
    public function customerOrders(OrderService $orderService)
    {
        if (! auth()->guard('customer')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = auth()->guard('customer')->user();
        $orders = $orderService->getByCustomer($customer->id);

        return response()->json($orders);
    }

    // create an order (for users or customers)
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        // Determine who is creating the order
        if (auth()->guard('customer')->check()) {
            // Customer is creating the order
            $customer = auth()->guard('customer')->user();
            $customerId = $customer->id;
            $creatorId = null; // no ERP user involved
        } else {
            // ERP user is creating the order
            //$this->authorize('create', Order::class);

            $customerId = $request->customer_id; // user can select customer
            $creatorId = auth()->id(); // store the user id
        }

        // Map items to DTOs
        $items = collect($request->items)
            ->map(fn ($item) => new CreateOrderItemDTO(
                product_id: $item['product_id'],
                quantity: $item['quantity']
            ))
            ->toArray();

        $dto = new CreateOrderDTO(
            customer_id: $customerId,
            items: $items
        );

        $order = $orderService->create($dto, $creatorId ?? $customerId);

        return response()->json($order, 201);
    }

    // approving order by admin
    public function approve(Order $order, OrderService $orderService)
    {
        $adminId = auth()->id();
        $this->authorize('approve', $order);
        $approvedOrder = $orderService->approve($order, $adminId);

        return response()->json($approvedOrder);
    }

    public function destroy(Order $order, OrderService $orderService)
    {
        //$this->authorize('delete', $order);
        $orderService->delete($order);

        return response()->json(null, 204);
    }



}
