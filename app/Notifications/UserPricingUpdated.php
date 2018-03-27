<?php

namespace App\Notifications;

use App\User;
use App\Model\Pricing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserPricingUpdated extends Notification
{
    use Queueable;

    private $user, $previous_pricing;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Pricing $previous_pricing)
    {
        $this->user = $user;
        $this->previous_pricing = $previous_pricing;
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
        return (new MailMessage)
                    ->greeting('Olá, seu plano foi modificado')
                    ->action('Abrir App',
                             'http://inserir-deep-link-para-o-app')
                    ->line("Seu plano era de ")
                    ->line($this->previous_pricing->name)
                    ->line("e foi atualizado para")
                    ->line($this->user->pricing->name);
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
            'previous_pricing' => $this->previous_pricing->toArray(),
            'user' => $this->user->toArray(),
        ];
    }
}
