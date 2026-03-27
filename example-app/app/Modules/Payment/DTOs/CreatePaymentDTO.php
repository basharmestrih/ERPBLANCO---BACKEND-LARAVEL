<?php

namespace Modules\Payment\DTOs;

class CreatePaymentDTO
{
    public function __construct(
        public readonly ?int $invoiceId,
        public readonly string $gateway,
        public readonly string $transactionId,
        public readonly ?string $chargeId,
        public readonly float $amount,
        public readonly string $currency,
        public readonly array $payload,
    ) {}
}