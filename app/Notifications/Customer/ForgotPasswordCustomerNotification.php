<?php

namespace App\Notifications\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordCustomerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct(private string $token)
    {
        //
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
            ->from(env('MAIL_FROM_ADDRESS', 'sercan@localkod.com'), 'Şifre Yenileme')
            ->subject('Şifre Yenileme')
            ->greeting('Şifrenizi Yenileyebilirsiniz !!!')
            ->line('Merhaba,')
            ->line('Şifrenizi Linke Tıklayarak Yenileyebilirsiniz.')
            ->action('Şifre Yenile', route('reset.password', ['token' => $this->token]))
            ->salutation('Saygılarımızla, Localkod');
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
