<?php

namespace Modules\Product\Models;

use Modules\Category\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Modules\Sales\Models\OrderItem;
use Modules\StockMovement\Models\StockMovement;
use Modules\ProductWarehouse\Models\ProductWarehouse;
class Product extends Model
{
    protected $fillable = ['name', 'price', 'total_quantity', 'category_id', 'unit_id'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMovements()
{
    return $this->hasMany(StockMovement::class);
}

    public function warehouses()
    {
        return $this->hasMany(ProductWarehouse::class);
    }
}
