<?php

namespace Modules\Payment\Services;

use Modules\Payment\Models\Payment;
use Modules\Payment\DTOs\CreatePaymentDTO;

class PaymentService
{
    public function create(CreatePaymentDTO $dto): Payment
    {
        return Payment::create([
            'invoice_id'            => $dto->invoiceId,
            'gateway'               => $dto->gateway,
            'gateway_transaction_id'=> $dto->transactionId,
            'gateway_charge_id'     => $dto->chargeId,
            'amount'                => $dto->amount,
            'currency'              => $dto->currency,
            'status'                => 'succeeded', 
            'gateway_response'      => $dto->payload,
        ]);
    }
}