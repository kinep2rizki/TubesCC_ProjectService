<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateGeneratedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $event;
    protected $certificate;

    /**
     * Create a new notification instance.
     */
    public function __construct($event, $certificate)
    {
        $this->event = $event;
        $this->certificate = $certificate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'certificate_id' => $this->certificate->id,
            'message' => 'Your certificate for ' . $this->event->title . ' is now available.',
            'url' => route('settings') . '#certificates'
        ];
    }
}
