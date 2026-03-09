<?php

namespace App\Notifications;

use App\Models\PhysicalBorrow;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationStatusNotification extends Notification
{
    public PhysicalBorrow $borrow;
    public string $status;

    public function __construct(PhysicalBorrow $borrow, string $status)
    {
        $this->borrow = $borrow;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->greeting('Hello ' . $notifiable->name . '!');

        if ($this->status === 'approved') {
            $message->subject('Reservation Approved - Digital Library')
                ->line('Your reservation has been approved!')
                ->line('Book: ' . $this->borrow->book->title)
                ->line('Accession No: ' . $this->borrow->book->accession_no)
                ->line('Please visit the library to claim your book.')
                ->action('View My Borrows', url('/student/borrows'));
        } elseif ($this->status === 'cancelled') {
            $message->subject('Reservation Cancelled - Digital Library')
                ->line('Unfortunately your reservation has been cancelled.')
                ->line('Book: ' . $this->borrow->book->title)
                ->line('Accession No: ' . $this->borrow->book->accession_no)
                ->line('Please contact the library for more information.')
                ->action('Browse Books', url('/student/books'));
        }

        return $message->line('Thank you!');
    }
}