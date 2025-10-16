<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApiVerifyEmail extends Notification
{
    protected string $verifyUrl;

    public function __construct(string $verifyUrl)
    {
        $this->verifyUrl = $verifyUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Email')
            ->line("Hello {$notifiable->name},")
            ->line('Please verify your email by clicking this link:')
            ->line($this->verifyUrl)
            ->line('If you did not create an account, ignore this email.');
    }
}
