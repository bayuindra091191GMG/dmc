<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovalPurchaseRequestCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $purchaseRequest;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($purchaseRequest, $user)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $prShowRoute = route('admin.purchase_requests.show', ['purchase_request' => $this->purchaseRequest->id]);

        $data =[
            'purchase_request'      => $this->purchaseRequest,
            'user'                  => $this->user,
            'url'                   => route('redirect', ['url' => $prShowRoute])
        ];

        return $this->from('hellbardx444@gmail.com')
            ->subject('Permintaan Approval PR')
            ->view('email.approval_purchase_request_created')
            ->with($data);
    }
}
