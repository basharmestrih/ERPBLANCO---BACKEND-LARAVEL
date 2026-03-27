<?php

namespace Modules\StockMovement\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\StockMovement\Models\StockMovement;

class StockMovementInRecorded
{
    use Dispatchable, SerializesModels;

    public StockMovement $stockMovement;

    public function __construct(StockMovement $stockMovement)
    {
        $this->stockMovement = $stockMovement;
    }
}
