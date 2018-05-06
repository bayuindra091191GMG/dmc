<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 14/03/2018
 * Time: 15:02
 */

namespace App\Transformer\Purchasing;


use App\Models\PurchaseInvoiceHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PurchaseInvoiceHeaderTransformer extends TransformerAbstract
{
    protected $mode = 'default';

    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function transform(PurchaseInvoiceHeader $header){
        try{
            $date = Carbon::parse($header->date)->format('d M Y');

            $piRoute = route('admin.purchase_invoices.show', ['purchase_invoice' => $header->id]);
            $poRoute = route('admin.purchase_orders.show', ['purchase_order' => $header->purchase_order_id]);

            $code = "<a style='text-decoration: underline;' href='" . $piRoute. "' target='_blank'>". $header->code. "</a>";
            $poCode =  "<a style='text-decoration: underline;' href='" . $poRoute. "' target='_blank'>". $header->purchase_order_header->code. "</a>";

            if($this->mode === 'default'){
                $action = "<a class='btn btn-xs btn-primary' href='purchase_invoices/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
                $action .= "<a class='btn btn-xs btn-info' href='purchase_invoices/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
            }else{
                $action = "<input type='checkbox' class='flat' id='chk". $header->id ."' name='chk[]' onclick='changeInput(". $header->id .");'/>";
                $action .= "<input type='text' id='" . $header->id ."' hidden='true' name='ids[]' value='' readonly />";
            }

            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $header->id ."' ><i class='fa fa-dollar'></i></a>";

            return[
                'code'              => $code,
                'po_code'           => $poCode,
                'supplier'          => $header->purchase_order_header->supplier->name,
                'total_price'       => $header->total_price_string,
                'total_discount'    => $header->total_discount_string ?? '-',
                'delivery_fee'      => $header->delivery_fee_string ?? '-',
                'total_payment'     => $header->total_payment_string,
                'repayment_amount'  => $header->repayment_amount_string,
                'created_at'        => $date,
                'action'            => $action
            ];
        }catch(\Exception $ex){
            error_log($ex);
        }
    }

}