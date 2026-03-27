<?php


namespace App\Providers;
use App\Models\User;

use App\Policies\AuthPolicy;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Policies\InvoicePolicy;
use Modules\Product\Models\Product;
use Modules\Product\Policies\ProductPolicy;
use Modules\Sales\Models\Order;
use Modules\Sales\Policies\OrderPolicy;
use Modules\StockMovement\Models\StockMovement;
use Modules\StockMovement\Policies\StockMovementPolicy;



use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => AuthPolicy::class,
        Order::class => OrderPolicy::class,
        Product::class => ProductPolicy::class,
        Invoice::class => InvoicePolicy::class,
        StockMovement::class => StockMovementPolicy::class,
       
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
