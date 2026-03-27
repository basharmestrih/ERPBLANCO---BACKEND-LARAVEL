<?php

namespace Modules\Invoice\States;

class Issued extends InvoiceState
{
    public function label(): string
    {
        return 'Issued';
    }
}
