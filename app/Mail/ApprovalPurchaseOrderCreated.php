<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalPurchaseOrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $purchaseOrder;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($purchaseOrder, $user)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $poShowRoute = route('admin.purchase_orders.show', ['purchase_order' => $this->purchaseOrder->id]);

        $data =[
            'purchase_request'      => $this->purchaseOrder,
            'user'                  => $this->user,
            'url'                   => route('redirect', ['url' => $poShowRoute])
        ];

        return $this->from('hellbardx444@gmail.com')
            ->subject('Permintaan Approval PR')
            ->view('email.approval_purchase_order_created')
            ->with($data);
    }
}
