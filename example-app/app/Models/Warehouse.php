<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\StockMovement\Models\StockMovement;
use Modules\ProductWarehouse\Models\ProductWarehouse;

class Warehouse extends Model
{
    protected $fillable = ['name', 'location'];

    public function stockMovements()
{
    return $this->hasMany(StockMovement::class);
}

public function products()
{
    return $this->hasMany(ProductWarehouse::class);
}

}
