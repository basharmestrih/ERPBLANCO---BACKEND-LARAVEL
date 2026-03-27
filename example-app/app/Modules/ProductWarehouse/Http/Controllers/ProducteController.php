<?php

namespace Modules\ProductWarehouse\Http\Controllers;
use Illuminate\Routing\Controller;
use Modules\ProductWarehouse\Services\ProductWarehouseService;
use App\Models\Warehouse;

class ProducteController extends Controller
{
    public function __construct(
        protected ProductWarehouseService $service
    ) {}

public function index(Warehouse $warehouse)
{
    return $this->service->getByWarehouse($warehouse->id);
}
}