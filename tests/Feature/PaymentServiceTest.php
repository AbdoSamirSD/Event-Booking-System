<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Booking;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_payment_service_creates_payment(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['price' => 100, 'quantity' => 5]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'ticket_id' => $ticket->id, 'quantity' => 2]);

        $service = new \App\Services\PaymentService();
        $payment = $service->pay($booking);

        $this->assertNotNull($payment);
        $this->assertContains($payment->status, ['success','failed']);
    }
}
