<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Sales\States\Order\Pending;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1️⃣ Drop old enum status
            $table->dropColumn('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            // 2️⃣ Add new state column
            $table->string('state')
                ->default(Pending::class)
                ->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'cancelled'])
                ->default('pending')
                ->after('created_by');
        });
    }
};