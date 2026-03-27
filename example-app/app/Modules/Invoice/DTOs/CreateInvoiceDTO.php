<?php

namespace Modules\Invoice\DTOs;

class CreateInvoiceDTO
{
    public function __construct(
        public int $order_id
    ) {
    }
}
