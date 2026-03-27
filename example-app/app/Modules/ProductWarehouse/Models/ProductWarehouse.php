<?php

namespace Modules\ProductWarehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;
use App\Models\Warehouse;

class ProductWarehouse extends Model
{
    protected $table = 'product_warehouse';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}