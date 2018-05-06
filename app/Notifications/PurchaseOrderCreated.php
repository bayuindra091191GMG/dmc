<?php

namespace App\Notifications;

use App\Models\PurchaseOrderHeader;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseOrderCreated extends Notification
{
    use Queueable;
    protected $purchaseOrder;
    protected $isMrCreator;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PurchaseOrderHeader $header, $isMrCreator)
    {
        $this->purchaseOrder = $header;
        $this->isMrCreator = $isMrCreator;
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
        $roleIds = [13];

        return [
            'document_type'             => 'Purchase Order',
            'po_id'                     => $this->purchaseOrder->id,
            'code'                      => $this->purchaseOrder->code,
            'mr_id'                     => $this->purchaseOrder->purchase_request_header->material_request_id,
            'mr_code'                   => $this->purchaseOrder->purchase_request_header->material_request_header->code,
            'mr_type'                   => $this->purchaseOrder->purchase_request_header->material_request_header->type,
            'sender_id'                 => $this->purchaseOrder->created_by,
            'sender_name'               => $this->purchaseOrder->createdBy->name,
            'receiver_id'               => 0,
            'receiver_role_id'          => $roleIds,
            'receiver_is_mr_creator'    => $this->isMrCreator
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
        $roleIds = [13];

        return [
            'id'        => $this->id,
            'read_at'   => null,
            'data'      => [
                'document_type'             => 'Purchase Order',
                'po_id'                     => $this->purchaseOrder->id,
                'code'                      => $this->purchaseOrder->code,
                'mr_id'                     => $this->purchaseOrder->purchase_request_header->material_request_id,
                'mr_code'                   => $this->purchaseOrder->purchase_request_header->material_request_header->code,
                'mr_type'                   => $this->purchaseOrder->purchase_request_header->material_request_header->type,
                'sender_id'                 => $this->purchaseOrder->created_by,
                'sender_name'               => $this->purchaseOrder->createdBy->name,
                'receiver_id'               => 0,
                'receiver_role_id'          => $roleIds,
                'receiver_is_mr_creator'    => $this->isMrCreator
            ],
        ];
    }
}
