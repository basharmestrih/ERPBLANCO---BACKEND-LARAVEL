<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Reporting\Services\InvoiceReportService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Reporting\Exports\InvoiceReportExport;

class GenerateDailyInvoiceReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(InvoiceReportService $service)
    {
        $invoices = $service->getDailyInvoices();

        Excel::store(
            new InvoiceReportExport($invoices),
            'reports/daily_' . now()->format('Y_m_d') . '.xlsx'
        );
    }
}