<?php
namespace Modules\Sales\States;

class Pending extends OrderState
{
    public function label(): string
    {
        return 'Pending';
    }
}
