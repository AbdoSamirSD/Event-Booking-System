<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $user = request()->user();
        $bookings = $user->bookings()->with('ticket.event')
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'message' => 'User bookings retrieved successfully',
            'bookings' => $bookings->map(function($booking) {
                return [
                    'booking_id' => $booking->id,
                    'ticket' => [
                        'ticket_id' => $booking->ticket->id,
                        'seat_number' => $booking->ticket->seat_number,
                        'price' => $booking->ticket->price,
                        'event' => [
                            'event_id' => $booking->ticket->event->id,
                            'title' => $booking->ticket->event->title,
                            'date' => $booking->ticket->event->date,
                            'location' => $booking->ticket->event->location,
                        ],
                    ],
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                ];
            })
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $ticketId)
    {
        //
        $ticket = Ticket::find($ticketId);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $existingBooking = Booking::where('user_id', $user->id)
            ->where('ticket_id', $ticketId)
            ->first();

        if ($existingBooking) {
            return response()->json(['message' => 'You have already booked this ticket'], 409);
        }

        $booking = Booking::create([
            'user_id' => $user->id,
            'ticket_id' => $ticketId,
            'quantity' => $request->input('quantity'),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if($booking->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking cancelled successfully'], 200);
    }
}
