<?php

namespace App\Notifications;

use App\Models\MaterialRequestHeader;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MaterialRequestCreated extends Notification implements ShouldQueue
{
    use Queueable;
    protected $materialRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MaterialRequestHeader $header)
    {
        $this->materialRequest = $header;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable){
        return [
            'id'        => $this->materialRequest->id,
            'code'      => $this->materialRequest->code
        ];
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
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'id' => $this->materialRequest->id,
                'code' => $this->materialRequest->code
            ],
        ];
    }
}
