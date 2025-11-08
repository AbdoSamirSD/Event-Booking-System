<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDoubleBooking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ticketId = $request->route('id');
        $userId = $request->user()->id;

        $existingBooking = \App\Models\Booking::where('ticket_id', $ticketId)
            ->where('user_id', $userId)
            ->where('status', '!=', 'canceled')
            ->first();

        if ($existingBooking) {
            return response()->json(['message' => 'You have already booked this ticket.'], 409);
        }

        return $next($request);
    }
}
