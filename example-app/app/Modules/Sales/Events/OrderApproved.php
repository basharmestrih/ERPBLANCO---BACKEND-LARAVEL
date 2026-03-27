<?php

namespace Modules\Sales\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Sales\Models\Order;

class OrderApproved
{
    use Dispatchable, SerializesModels;

    public Order $order;
    public int $approvedBy;

    public function __construct(Order $order, int $approvedBy)
    {
        $this->order = $order;
        $this->approvedBy = $approvedBy;
    }
}
