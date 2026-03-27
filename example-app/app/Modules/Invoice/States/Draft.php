<?php

namespace Modules\Invoice\States;

class Draft extends InvoiceState
{
    public function label(): string
    {
        return 'Draft';
    }
}
