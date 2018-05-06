<?php

namespace App\Notifications;

use App\Models\ItemReceiptHeader;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GoodsReceiptCreated extends Notification
{
    use Queueable;
    protected $goodsReceipt;
    protected $isMrCreator;
    protected $isPrCreator;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ItemReceiptHeader $header, $isMrCreator, $isPrCreator)
    {
        $this->goodsReceipt = $header;
        $this->isMrCreator = $isMrCreator;
        $this->isPrCreator = $isPrCreator;
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
            'document_type'             => 'Goods Receipt',
            'gr_id'                     => $this->goodsReceipt->id,
            'code'                      => $this->goodsReceipt->code,
            'sender_id'                 => $this->goodsReceipt->created_by,
            'sender_name'               => $this->goodsReceipt->createdBy->name,
            'receiver_id'               => 0,
            'receiver_role_id'          => $roleIds,
            'receiver_is_mr_creator'    => $this->isMrCreator,
            'receiver_is_pr_creator'    => $this->isPrCreator
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
                'document_type'             => 'Goods Receipt',
                'gr_id'                     => $this->goodsReceipt->id,
                'code'                      => $this->goodsReceipt->code,
                'sender_id'                 => $this->goodsReceipt->created_by,
                'sender_name'               => $this->goodsReceipt->createdBy->name,
                'receiver_id'               => 0,
                'receiver_role_id'          => $roleIds,
                'receiver_is_mr_creator'    => $this->isMrCreator,
                'receiver_is_pr_creator'    => $this->isPrCreator
            ],
        ];
    }
}
