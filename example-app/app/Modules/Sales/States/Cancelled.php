<?php
namespace Modules\Sales\States;

class Cancelled extends OrderState
{
    public function label(): string
    {
        return 'Cancelled';
    }
}