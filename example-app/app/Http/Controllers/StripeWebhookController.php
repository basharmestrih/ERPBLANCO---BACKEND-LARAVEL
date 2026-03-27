<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Events\StripePaymentIntentSucceeded;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $secret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe signature verification failed');
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload');
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        Log::info('Stripe webhook received', [
            'type' => $event->type
        ]);

        if ($event->type === 'payment_intent.succeeded') {
            event(new StripePaymentIntentSucceeded(
                $event->toArray()
            ));
        }

        return response()->json(['status' => 'success']);
    }
}