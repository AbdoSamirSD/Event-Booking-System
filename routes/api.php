<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/events', [EventController::class, 'index']); // pagination, search, filter by date, location
    Route::get('/events/{id}', [EventController::class, 'show']); // view event details with tickets
    Route::post('/events', [EventController::class, 'store'])->middleware(['role:organizer']);
    Route::put('/events/{id}', [EventController::class, 'update'])->middleware(['role:organizer']);
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->middleware(['role:organizer']);

    // Tickets
    Route::post('/events/{id}/tickets', [TicketController::class, 'store'])->middleware(['role:organizer']);
    Route::put('/tickets/{id}', [TicketController::class, 'update'])->middleware(['role:organizer']);
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->middleware(['role:organizer']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->middleware(['role:customer']);
    Route::post(('/tickets/{ticketId}/bookings'), [BookingController::class, 'store'])->middleware(['role:customer', 'prevent.double']);
    Route::delete('/bookings/{id}/cancel', [BookingController::class, 'destroy'])->middleware(['role:customer']);

    // Payments
    Route::post('/bookings/{id}/payments', [PaymentController::class, 'store'])->middleware(['role:customer']);
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->middleware(['role:customer']);
});
