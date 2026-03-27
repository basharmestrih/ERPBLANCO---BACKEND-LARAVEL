<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('gateway'); // stripe
            $table->string('gateway_transaction_id'); // payment intent id
            $table->string('gateway_charge_id')->nullable();

            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);

            $table->string('status'); // pending, succeeded, failed

            $table->timestamp('paid_at')->nullable();

            $table->json('gateway_response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};