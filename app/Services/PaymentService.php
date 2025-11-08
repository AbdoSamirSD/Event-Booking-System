<?php

namespace App\Services;
use App\Models\Booking;
use App\Models\Payment;

class PaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function pay(Booking $booking){
        $success = rand(1, 100) <= 90; // 90% success rate
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $booking->ticket->price,
            'status' => $success ? 'success' : 'failed',
        ]);

        if ($success) {
            $booking->status = 'confirmed';
            $booking->save();
        } else {
            $booking->status = 'pending';
            $booking->save();
        }

        return $payment;
    }
}
