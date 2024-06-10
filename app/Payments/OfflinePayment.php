<?php

namespace App\Payments;

use App\Models\Transaction;

class OfflinePayment extends AbstractGateway
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): PaymentAuthorize
    {
        if (!$this->order) {
        }

        $this->order->update([
            'status' => $this->config['authorized'] ?? null,
            'placed_at' => now(),
        ]);

        return new PaymentAuthorize(true);
    }

    /**
     * {@inheritDoc}
     */
    public function refund(Transaction $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        return new PaymentRefund(true);
    }

    /**
     * {@inheritDoc}
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture
    {
        return new PaymentCapture(true);
    }
}
