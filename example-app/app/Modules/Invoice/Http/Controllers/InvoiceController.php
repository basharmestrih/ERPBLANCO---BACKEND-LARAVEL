<?php

namespace Modules\Invoice\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use Illuminate\Http\Request;
use Modules\Invoice\DTOs\CreateInvoiceDTO;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Services\InvoiceService;
use Modules\Sales\Models\Order;

class InvoiceController extends Controller
{
    public function store(Order $order, InvoiceService $invoiceService)
    {
        $this->authorize('create', Invoice::class);

        $dto = new CreateInvoiceDTO(order_id: $order->id);
        $invoice = $invoiceService->create($dto, auth()->id());

        return (new InvoiceResource($invoice))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request, InvoiceService $invoiceService)
    {
        $filters = $request->validate([
            'status' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'total' => ['nullable', 'numeric', 'min:0'],
        ]);

        return InvoiceResource::collection($invoiceService->getAll($filters));
    }

    // PAYMENT METHOD
    public function pay(Invoice $invoice, Request $request)
    {
        #$this->authorize('pay', $invoice);

        if ($invoice->payment_status === 'paid') {
            return response()->json(['message' => 'Already paid'], 400);
        }

        $user = auth()->user();

        $payment = $user->pay((int) round($invoice->total_amount * 100));
         //Log::info('pay done');

        $invoice->update([
            'payment_intent_id' => $payment->asStripePaymentIntent()->id,
        ]);

        return response()->json([
            'client_secret' => $payment->asStripePaymentIntent()->client_secret,
        ]);
    }
}
