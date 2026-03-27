<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Invoice\States\Draft;
use Modules\Invoice\States\Issued;
use Modules\Invoice\States\Paid;
use Modules\Invoice\States\PartiallyPaid;
use Modules\Invoice\States\Refunded;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('state')->default(Draft::class)->after('total_amount');
        });

        DB::table('invoices')->where('status', 'draft')->update(['state' => Draft::class]);
        DB::table('invoices')->where('status', 'issued')->update(['state' => Issued::class]);
        DB::table('invoices')->where('status', 'partially_paid')->update(['state' => PartiallyPaid::class]);
        DB::table('invoices')->where('status', 'paid')->update(['state' => Paid::class]);
        DB::table('invoices')->where('status', 'refunded')->update(['state' => Refunded::class]);
        DB::table('invoices')->where('status', 'cancelled')->update(['state' => Refunded::class]);

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('status', ['draft', 'issued', 'partially_paid', 'paid', 'refunded'])
                ->default('draft')
                ->after('total_amount');
        });

        DB::table('invoices')->where('state', Draft::class)->update(['status' => 'draft']);
        DB::table('invoices')->where('state', Issued::class)->update(['status' => 'issued']);
        DB::table('invoices')->where('state', PartiallyPaid::class)->update(['status' => 'partially_paid']);
        DB::table('invoices')->where('state', Paid::class)->update(['status' => 'paid']);
        DB::table('invoices')->where('state', Refunded::class)->update(['status' => 'refunded']);

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
