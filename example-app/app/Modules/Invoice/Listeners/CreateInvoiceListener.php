<?php

namespace Modules\Invoice\Listeners;

use Modules\Invoice\DTOs\CreateInvoiceDTO;
use Modules\Invoice\Services\InvoiceService;
use Modules\Sales\Events\OrderApproved;

class CreateInvoiceListener
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function handle(OrderApproved $event): void
    {
        $dto = new CreateInvoiceDTO(order_id: $event->order->id);
        $this->invoiceService->create($dto, $event->approvedBy);
    }
}
