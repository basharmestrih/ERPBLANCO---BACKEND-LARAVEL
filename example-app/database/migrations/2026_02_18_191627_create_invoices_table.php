<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Invoice\States\Invoice\Draft;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Drop old enum status
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // 2️⃣ Add new state column
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('state')
                ->default(Draft::class)
                ->after('total_amount');
        });
    }

    public function down(): void
    {
        // Rollback: remove state
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        // Restore old enum
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('status', [
                'issued',
                'paid',
                'partially_paid',
                'cancelled'
            ])->default('issued')
              ->after('total_amount');
        });
    }
};