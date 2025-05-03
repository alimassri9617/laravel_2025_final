<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DeliveryAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $delivery;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $delivery
     * @return void
     */
    public function __construct($delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\FcmMessage
     */
    public function toFcm($notifiable)
    {
        return (new \NotificationChannels\Fcm\FcmMessage())
            ->content([
                'title' => 'New Delivery Assigned',
                'body' => 'You have been assigned a new delivery request. Please check your dashboard.',
                'sound' => 'default',
            ])
            ->data([
                'delivery_id' => $this->delivery->id,
                'status' => $this->delivery->status,
            ]);
    }
}
