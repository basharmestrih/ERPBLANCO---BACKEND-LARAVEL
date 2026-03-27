<?php

namespace Modules\Reporting\Services;

use Modules\Invoice\Models\Invoice;
use Carbon\Carbon;

class InvoiceReportService
{
    public function getDailyInvoices()
    {
        return Invoice::with(['customer', 'items.product'])
            ->whereDate('issued_at', Carbon::today())
            ->get();
    }

    public function getWeeklyInvoices()
    {
        return Invoice::with(['customer', 'items.product'])
            ->whereBetween('issued_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])
            ->get();
    }
}