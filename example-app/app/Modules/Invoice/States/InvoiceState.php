<?php

namespace Modules\Invoice\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class InvoiceState extends State
{
    abstract public function label(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, Issued::class)
            ->allowTransition(Issued::class, PartiallyPaid::class)
            ->allowTransition(PartiallyPaid::class, Paid::class)
            ->allowTransition(Paid::class, Refunded::class);
    }
}
