<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SetPasswordNotification extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url('/set-password?token=' . $this->token . '&email=' . urlencode($notifiable->email));

        return (new MailMessage)
            ->subject('Set Your Password - Digital Library')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your account has been created in the Digital Library System.')
            ->line('Please click the button below to set your password.')
            ->action('Set Password', $url)
            ->line('This link will expire in 48 hours.')
            ->line('If you did not expect this email, please ignore it.');
    }
}