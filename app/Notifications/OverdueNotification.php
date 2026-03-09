<?php

namespace App\Notifications;

use App\Models\PhysicalBorrow;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OverdueNotification extends Notification
{
    public PhysicalBorrow $borrow;

    public function __construct(PhysicalBorrow $borrow)
    {
        $this->borrow = $borrow;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $daysOverdue = now()->diffInDays($this->borrow->due_date);

        return (new MailMessage)
            ->subject('Overdue Book Notice - Digital Library')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder that you have an overdue book.')
            ->line('Book: ' . $this->borrow->book->title)
            ->line('Accession No: ' . $this->borrow->book->accession_no)
            ->line('Due Date: ' . $this->borrow->due_date->format('F d, Y'))
            ->line('Days Overdue: ' . $daysOverdue . ' day(s)')
            ->line('Please return the book immediately to avoid additional fines.')
            ->action('View My Borrows', url('/student/borrows'))
            ->line('Thank you!');
    }
}