<?php

namespace Modules\Reporting\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoiceReportExport implements FromCollection, WithHeadings
{
    protected Collection $invoices;

    public function __construct(Collection $invoices)
    {
        $this->invoices = $invoices;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->invoices as $invoice) {
            foreach ($invoice->items as $item) {
                $rows->push([
                    'Invoice Number' => $invoice->invoice_number,
                    'Customer' => $invoice->customer->name ?? '',
                    'Invoice Date' => $invoice->issued_at,
                    'Due Date' => $invoice->due_date,
                    'State' => $invoice->state->label(),
                    'Product' => $item->product->name ?? '',
                    'Quantity' => $item->quantity,
                    'Unit Price' => $item->unit_price,
                    'Subtotal' => $item->subtotal,
                    'Invoice Total' => $invoice->total_amount,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Customer',
            'Invoice Date',
            'Due Date',
            'State',
            'Product',
            'Quantity',
            'Unit Price',
            'Subtotal',
            'Invoice Total',
        ];
    }
}