<?php

namespace App\Notifications\SubscribedNewsletter;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewsletterSubscribedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Subscriber $subscriber)
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
            'message' => 'A_NEW_SUBSCRIBER_HAS_SUBSCRIBED_TO_THE_WEBSITE_NEWSLETTER',
            'subscriber' => $this->subscriber,
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
            'message' => 'A_NEW_SUBSCRIBER_HAS_SUBSCRIBED_TO_THE_WEBSITE_NEWSLETTER',
            'subscriber' => $this->subscriber,
        ]);
    }
}
