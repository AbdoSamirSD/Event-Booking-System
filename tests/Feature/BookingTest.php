<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_customer_can_book_ticket(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $organizer = User::factory()->create([
            'role' => 'organizer',
        ]);

        $event = Event::factory()->create([
            'created_by' => $organizer->id,
        ]);

        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'quantity' => 3,
        ]);

        $response = $this->actingAs($customer, 'sanctum')->postJson("/api/tickets/{$ticket->id}/bookings",[
            'quantity' => 2,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message', 'booking' => ['id', 'user_id', 'ticket_id', 'quantity', 'status']
        ]);
    }
}
