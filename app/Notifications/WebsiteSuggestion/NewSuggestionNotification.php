<?php

namespace App\Notifications\WebsiteSuggestion;

use App\Models\Suggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSuggestionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Suggestion $suggestion)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
    * Get the array representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return array<string|int>
    */
    public function toArray($notifiable)
    {
        return [
            "message" =>$this->suggestion->type==='report_bug' ? "A new suggestion about of reporting bugs." : "A new suggestion about of feature requested.",
            "suggestion"=>$this->suggestion
        ];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            "message" =>$this->suggestion->type==='report_bug' ? "A new suggestion about of reporting bugs." : "A new suggestion about of feature requested.",
            "suggestion"=>$this->suggestion
        ]);
    }
}