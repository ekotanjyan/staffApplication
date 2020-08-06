<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $token;
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->greeting(__('Hello')." {$notifiable->name},")
            ->subject(__('Password reset requested for your account at LiUU'))
            ->line(__('Somebody (hopefully you) requested a new password for the LiUU account :email. No changes have been made to your account yet.',['email'=>$notifiable->email]))
            ->line(__('You can reset your password by clicking the link below:'))
            ->action(__('Reset Password'), $url)
            ->line(__('This password reset link will expire in :count hours.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire') / 60]))
            ->line(__('If you did not request a password reset, please reply to this email or forward it to security@liuu.world'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
