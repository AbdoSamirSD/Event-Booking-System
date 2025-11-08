<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        //
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Confirmed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your booking for the event "' . $this->booking->event->title . '" has been confirmed.')
            ->line('Booking Details:')
            ->line('Event: ' . $this->booking->event->title)
            ->line('Date: ' . $this->booking->event->date->toDayDateString())
            ->line('Location: ' . $this->booking->event->location)
            ->line('Ticket Type: ' . $this->booking->ticket->type)
            ->line('Amount Paid: $' . number_format($this->booking->ticket->price, 2))
            ->line('Thank you for booking with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
