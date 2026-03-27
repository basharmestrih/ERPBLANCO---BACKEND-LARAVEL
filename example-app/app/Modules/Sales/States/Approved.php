<?php
namespace Modules\Sales\States;

class Approved extends OrderState
{
    public function label(): string
    {
        return 'Approved';
    }
}