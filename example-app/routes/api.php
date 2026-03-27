<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use Modules\Category\Http\Controllers\CategoryController;
use Modules\Customer\Http\Controllers\CustomerController;
use Modules\Invoice\Http\Controllers\InvoiceController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\ProductWarehouse\Http\Controllers\ProducteController;
use Modules\Unit\Http\Controllers\UnitController;
use Modules\Sales\Http\Controllers\OrderController;
use Modules\StockMovement\Http\Controllers\StockMovementController;
use Modules\Warehouse\Http\Controllers\WarehouseController;
use Laravel\Cashier\Http\Controllers\WebhookController;
use App\Http\Controllers\StripeWebhookController;
use Modules\Reporting\Http\Controllers\DashboardCacheController;
use Modules\Reporting\Http\Controllers\InvoiceReportController;

    // Authentication
    Route::post('/login', [AuthController::class, 'login'])->name('login');
   
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);
    Route::get('/products', [ProductController::class, 'index']);


    /*Route::prefix('customers')->group(function () {
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/login', [CustomerAuthController::class, 'login']);
    });*/

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);


    //Stocks
    Route::get('/stock-movements', [StockMovementController::class, 'index']);

    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/units', [UnitController::class, 'index']);
    Route::get('/productwarehouses/{warehouse}', [ProducteController::class, 'index']);
    Route::get('/warehouses', [WarehouseController::class, 'index']);
    Route::post('/warehouses', [WarehouseController::class, 'store']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/units', [UnitController::class, 'index']);





    // Protected routes (customer auth)
    Route::middleware(['auth:sanctum', 'customer'])->prefix('customers')->group(function () {
        Route::get('/me', [CustomerAuthController::class, 'me']);
        Route::get('/orders', [OrderController::class, 'customerOrders']);

    });




    // REPORTING
    //test dahsboard refresh 
Route::prefix('reports')->group(function () {
    Route::get('/dashboard', [DashboardCacheController::class, 'show']);
    Route::post('/dashboard/refresh', [DashboardCacheController::class, 'refresh']);
    Route::get('/invoices/daily', [InvoiceReportController::class, 'daily']);
    Route::get('/invoices/weekly', [InvoiceReportController::class, 'weekly']);
});


    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/orders/{order}/approve', [OrderController::class, 'approve']);



    // user management
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/roles', [AuthController::class, 'roles']);
    Route::post('/users', [AuthController::class, 'register']);
    Route::put('/users/{id}', [AuthController::class, 'update']);
    Route::post('/users/{id}/role', [AuthController::class, 'assignRole']);
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);
    Route::post('/stock-movements', [StockMovementController::class, 'store']);







    Route::post('/orders/{order}/invoice', [InvoiceController::class, 'store']);
    Route::get('/orders/invoices', [InvoiceController::class, 'index']);



    // Product CRUD (protected)
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/units', [UnitController::class, 'store']);


    // Orders






     // STRIPE WEBHOOK
     // PAYMENT ENDPOINT
    
     Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay']);

    });
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);




   



