<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Announcement;

class NewAnnouncement extends Notification
{
    use Queueable;

    // Variable to hold  data
    public $announcement; 
    

    /**
     * Create a new notification instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // 'database' stores it for the navbar bell
        // 'mail' sends the email
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Announcement: ' . $this->announcement->title)
            ->line('A new announcement has been posted.')
            ->line('"' . $this->announcement->message . '"') // Show the message
            ->action('View Board', route('announcements.index'))
            ->line('Thank you for being part of our school!');
    }

    /**
     * Get the array representation of the notification (for the database bell-icon).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Announcement',
            'message' => 'New post: ' . $this->announcement->title,
            'url' => route('announcements.index'),
            'type' => 'announcement'
        ];
    }
}
