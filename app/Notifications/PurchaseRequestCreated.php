<?php

namespace App\Notifications;

use App\Models\PurchaseRequestHeader;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseRequestCreated extends Notification
{
    use Queueable;
    protected $purchaseRequest;
    protected $isCreator;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PurchaseRequestHeader $header, $isCreator)
    {
        $this->purchaseRequest = $header;
        $this->isCreator = $isCreator;
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
        $roleIds = [12,13];

        return [
            'document_type'         => 'Purchase Request',
            'pr_id'                 => $this->purchaseRequest->id,
            'code'                  => $this->purchaseRequest->code,
            'mr_id'                 => $this->purchaseRequest->material_request_id,
            'mr_code'               => $this->purchaseRequest->material_request_header->code,
            'mr_type'               => $this->purchaseRequest->material_request_header->type,
            'sender_id'             => $this->purchaseRequest->created_by,
            'sender_name'           => $this->purchaseRequest->createdBy->name,
            'receiver_id'           => 0,
            'receiver_role_id'      => $roleIds,
            'receiver_is_creator'   => $this->isCreator
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
        $roleIds = [12,13];

        return [
            'id'        => $this->id,
            'read_at'   => null,
            'data'      => [
                'document_type'         => 'Purchase Request',
                'pr_id'                 => $this->purchaseRequest->id,
                'code'                  => $this->purchaseRequest->code,
                'mr_id'                 => $this->purchaseRequest->material_request_id,
                'mr_code'               => $this->purchaseRequest->material_request_header->code,
                'mr_type'               => $this->purchaseRequest->material_request_header->type,
                'sender_id'             => $this->purchaseRequest->created_by,
                'sender_name'           => $this->purchaseRequest->createdBy->name,
                'receiver_id'           => 0,
                'receiver_role_id'      => $roleIds,
                'receiver_is_creator'   => $this->isCreator
            ],
        ];
    }
}
