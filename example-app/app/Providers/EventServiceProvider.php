<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\StripePaymentIntentSucceeded;
use App\Modules\Invoice\Listeners\MarkInvoiceAsPaidListener;
use Modules\Payment\Listeners\RecordStripePaymentListener;
use Modules\ProductWarehouse\Listeners\UpdateProductWarehouseListener;
use Modules\ProductWarehouse\Listeners\DecreaseProductWarehouseListener;
use Modules\StockMovement\Events\StockMovementInRecorded;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \Modules\Sales\Events\OrderApproved::class => [
            \Modules\StockMovement\Listeners\CreateStockMovementListener::class,
            DecreaseProductWarehouseListener::class,
            \Modules\Invoice\Listeners\CreateInvoiceListener::class,
        ],

        StockMovementInRecorded::class => [
            UpdateProductWarehouseListener::class,
        ],

        StripePaymentIntentSucceeded::class => [
            MarkInvoiceAsPaidListener::class,
            RecordStripePaymentListener::class,
            \Listeners\UpdateCustomerAnalytics::class,
        ],

    ];



    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
