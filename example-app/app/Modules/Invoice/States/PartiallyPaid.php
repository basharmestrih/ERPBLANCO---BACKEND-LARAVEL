<?php

namespace Modules\Invoice\States;

class PartiallyPaid extends InvoiceState
{
    public function label(): string
    {
        return 'Partially Paid';
    }
}
