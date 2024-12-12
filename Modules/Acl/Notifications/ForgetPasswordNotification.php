<?php

namespace Modules\Acl\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
//todo change
class ForgetPasswordNotification extends Notification
{
    use Queueable;

    private string $token;

    /**
     * Create a new notification instance.
     *
     * return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * param  mixed  $notifiable
     * return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $url = (setting("app_url")??env('VUE_URL')) . "/reset-password/" . $this->token;

        return (new MailMessage)
            ->subject('Reset password')
            ->greeting('Password reset Please click on the following link Type the new password')
            ->action('Reset Password', $url)
            ->line('Thank you');
    }
}
