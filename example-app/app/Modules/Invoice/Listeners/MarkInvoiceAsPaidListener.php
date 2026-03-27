<?php

namespace App\Modules\Invoice\Listeners;

use App\Events\StripePaymentIntentSucceeded;
use Illuminate\Support\Facades\Log;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\States\Paid;

class MarkInvoiceAsPaidListener
{
    public function handle(StripePaymentIntentSucceeded $event): void
    {
        Log::info('MarkInvoiceAsPaid listener triggered');

        try {
            $paymentIntentId = $event->payload['data']['object']['id'] ?? null;

            Log::info('Payment Intent ID received', [
                'payment_intent_id' => $paymentIntentId,
            ]);

            if (! $paymentIntentId) {
                Log::warning('No payment_intent_id found in webhook payload');
                return;
            }

            $invoice = Invoice::where('payment_intent_id', $paymentIntentId)->first();

            if (! $invoice) {
                Log::warning('Invoice not found', [
                    'payment_intent_id' => $paymentIntentId,
                ]);
                return;
            }

            if ($invoice->payment_status === 'paid') {
                Log::info('Invoice already marked as paid');
                return;
            }

            $invoice->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            if ($invoice->state->canTransitionTo(Paid::class)) {
                $invoice->state->transitionTo(Paid::class);
            }

            Log::info('Invoice marked as paid successfully', [
                'invoice_id' => $invoice->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error in MarkInvoiceAsPaid', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
