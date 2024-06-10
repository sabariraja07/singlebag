<?php

namespace App\Gateways;

use Illuminate\Http\Request;
use App\Events\BookingCreatedEvent;

class OfflinePaymentGateway extends BaseGateway
{
    public $name = 'Offline Payment';

    public function process(Request $request, $bookings)
    {
        foreach ($bookings as $key => $booking) {
            $service = $booking->service;

            $service->beforePaymentProcess($booking, $this);
            // Simple change status to processing
            $booking->markAsProcessing($this, $service);
            $booking->sendNewBookingEmails();

            event(new BookingCreatedEvent($booking));

            $service->afterPaymentProcess($booking, $this);
        }

        $booking = $bookings->first();

        return response()->json([
            'url' => $booking->getDetailUrl()
        ])->send();
    }
}
