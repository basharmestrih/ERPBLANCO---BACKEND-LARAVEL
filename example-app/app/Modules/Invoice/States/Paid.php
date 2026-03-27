<?php

namespace Modules\Invoice\States;

class Paid extends InvoiceState
{
    public function label(): string
    {
        return 'Paid';
    }
}
