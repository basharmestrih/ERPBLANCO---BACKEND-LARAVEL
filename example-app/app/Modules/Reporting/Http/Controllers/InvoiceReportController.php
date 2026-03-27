<?php

namespace Modules\Reporting\Http\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Reporting\Exports\InvoiceReportExport;
use Modules\Reporting\Services\InvoiceReportService;

class InvoiceReportController extends Controller
{
    public function daily(InvoiceReportService $service)
    {
        $invoices = $service->getDailyInvoices();

        return Excel::download(
            new InvoiceReportExport($invoices),
            'daily_invoices.xlsx'
        );
    }

    public function weekly(InvoiceReportService $service)
    {
        $invoices = $service->getWeeklyInvoices();

        return Excel::download(
            new InvoiceReportExport($invoices),
            'weekly_invoices.xlsx'
        );
    }
}