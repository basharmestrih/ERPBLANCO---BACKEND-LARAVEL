<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_caches', function (Blueprint $table) {
            $table->id();
            $table->string('key')->default('default')->unique();
            $table->unsignedBigInteger('total_orders')->default(0);
            $table->decimal('total_orders_cost', 20, 2)->default(0);
            $table->unsignedBigInteger('pending_invoices')->default(0);
            $table->json('low_inventory')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_caches');
    }
};
