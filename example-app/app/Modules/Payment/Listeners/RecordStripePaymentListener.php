<?php

namespace Modules\Payment\Listeners;

use App\Events\StripePaymentIntentSucceeded;
use Modules\Payment\Services\PaymentService;
use Modules\Payment\DTOs\CreatePaymentDTO;
use Illuminate\Support\Facades\Log;

class RecordStripePaymentListener
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function handle(StripePaymentIntentSucceeded $event): void
    {

        $intent = $event->payload['data']['object'];

        $charge = $intent['charges']['data'][0] ?? null;

        $dto = new CreatePaymentDTO(
            invoiceId: null, 
            gateway: 'stripe',
            transactionId: $intent['id'],
            chargeId: $charge['id'] ?? null,
            amount: $intent['amount_received'] / 100,
            currency: $intent['currency'],
            
            payload: $event->payload
        );

        $this->paymentService->create($dto);
    }
}