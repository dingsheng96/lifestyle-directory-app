<?php

namespace App\Notifications;

use App\Models\TacNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendTacNumber extends Notification
{
    use Queueable;

    private $tac;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $tac)
    {
        $this->tac = $tac;
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
        $tac_number = $this->tac['raw_tac'];
        $expired_at = $this->tac['expired_at'];
        $message_path = 'mail.' . $this->tac['purpose'] . '.';

        return (new MailMessage)
            ->subject(__($message_path . 'subject'))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__($message_path . 'line_1', ['code' => $tac_number, 'expiry' => $expired_at]))
            ->line(__($message_path . 'line_2'));
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
