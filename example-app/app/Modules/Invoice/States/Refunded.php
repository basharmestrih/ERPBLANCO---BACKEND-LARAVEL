<?php

namespace Modules\Invoice\States;

class Refunded extends InvoiceState
{
    public function label(): string
    {
        return 'Refunded';
    }
}
