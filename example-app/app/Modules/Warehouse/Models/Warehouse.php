<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ProductWarehouse\Models\ProductWarehouse;
use Modules\StockMovement\Models\StockMovement;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'location',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function products()
    {
        return $this->hasMany(ProductWarehouse::class);
    }
}
