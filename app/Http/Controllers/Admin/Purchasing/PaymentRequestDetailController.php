<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 27/03/2018
 * Time: 10:35
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestsPiDetail;
use App\Models\PaymentRequestsPoDetail;
use App\Models\PurchaseInvoiceHeader;
use App\Models\PurchaseOrderHeader;
use Faker\Provider\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PaymentRequestDetailController extends Controller
{
    public function store(Request $request){
        try{
            $type = $request->input('type');

            $headerId = $request->input('header_id');
            if($type == 'PI'){
                if(!$request->filled('pi_id')){
                    return Response::json(array('errors' => 'pi_required'));
                }

                $piId = $request->input('pi_id');

                if(PaymentRequestsPiDetail::where('payment_request_id', $headerId)
                    ->where('purchase_invoice_header_id', $piId)
                    ->exists()){
                    return Response::json(array('errors' => 'pi_exists'));
                }

                $rfpPiDetail = new PaymentRequestsPiDetail();
                $rfpPiDetail->payment_requests_id = $headerId;
                $rfpPiDetail->purchase_invoice_header_id = $piId;
                $rfpPiDetail->save();

                $json = PurchaseInvoiceHeader::find($piId);
            }
            else{
                if(!$request->filled('po_id')){
                    return Response::json(array('errors' => 'po_required'));
                }

                $poId = $request->input('po_id');

                if(PaymentRequestsPiDetail::where('payment_request_id', $headerId)
                    ->where('purchase_order_id', $poId)
                    ->exists()){
                    return Response::json(array('errors' => 'po_exists'));
                }

                $rfpPoDetail = new PaymentRequestsPoDetail();
                $rfpPoDetail->payment_requests_id = $headerId;
                $rfpPoDetail->purchase_order_id = $poId;
                $rfpPoDetail->save();

                $json = PurchaseOrderHeader::find($poId);
            }

            return new JsonResponse($json);
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'error'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request){
        try{
            $type = $request->input('type');

            $headerId = $request->input('header_id');
            $detailId = $request->input('detail_id');
            $ppn = 0;
            $pph_23 = 0;
            $total_amount = 0;
            $amount = 0;

            if($type == 'PI'){
                if(!$request->filled('pi_id')){
                    return Response::json(array('errors' => 'pi_required'));
                }

                $piId = $request->input('pi_id');

                $rfpPiDetail = PaymentRequestsPiDetail::find($detailId);

                if(empty($rfpPiDetail)){
                    return Response::json(array('errors' => 'pi_deleted'));
                }

                $rfpPiDetail->purchase_invoice_header_id = $piId;
                $rfpPiDetail->save();

                // Update header
                $header = PaymentRequest::find($headerId);
                foreach($header->payment_requests_pi_details as $detail){
                    $ppn += $detail->purchase_invoice_header->ppn_amount;
                    $pph_23 += $detail->purchase_invoice_header->pph_amount;
                    $amount += $detail->purchase_invoice_header->total_price;
                    $total_amount += $detail->purchase_invoice_header->total_payment;
                }
                $header->amount = $amount;
                $header->total_amount = $total_amount;

                if($header->type == 'default'){
                    $header->ppn = $ppn;
                    $header->pph_23 = $pph_23;
                }
                else{
                    $header->ppn = 0;
                    $header->pph_23 = 0;
                }
                $header->save();

                $json = PaymentRequestsPiDetail::with('purchase_invoice_header')->find($detailId);
            }
            else{
                if(!$request->filled('po_id')){
                    return Response::json(array('errors' => 'po_required'));
                }

                $poId = $request->input('po_id');

                $rfpPoDetail = PaymentRequestsPoDetail::find($detailId);

                if(empty($rfpPoDetail)){
                    return Response::json(array('errors' => 'po_deleted'));
                }

                $rfpPoDetail->purchase_order_id = $poId;
                $rfpPoDetail->save();

                // Update header
                $header = PaymentRequest::find($headerId);
                foreach($header->payment_requests_po_details as $detail){
                    $ppn += $detail->purchase_order_header->ppn_amount;
                    $pph_23 += $detail->purchase_order_header->pph_amount;
                    $amount += $detail->purchase_order_header->total_price;
                    $total_amount += $detail->purchase_order_header->total_payment;
                }
                $header->amount = $amount;
                $header->total_amount = $total_amount;

                if($header->type == 'default'){
                    $header->ppn = $ppn;
                    $header->pph_23 = $pph_23;
                }
                else{
                    $header->ppn = 0;
                    $header->pph_23 = 0;
                }
                $header->save();

                $json = PaymentRequestsPoDetail::with('purchase_order_header')->find($detailId);
            }

            return new JsonResponse($json);
        }
        catch (\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'error'));
        }
    }

    public function delete(Request $request){
        try{
            $type = $request->input('type');
            $headerId = $request->input('header_id');
            $detailId = $request->input('detail_id');

            $ppn = 0;
            $pph_23 = 0;
            $total_amount = 0;
            $amount = 0;
            $detail = null;
            if($type === 'PI'){
                if(PaymentRequestsPiDetail::where('payment_requests_id', $headerId)->get()->count() == 1){
                    return Response::json(array('errors' => 'pi_last'));
                }

                $detail = PaymentRequestsPiDetail::find($detailId);

                if(empty($detail)){
                    return Response::json(array('errors' => 'pi_deleted'));
                }

                $detail->delete();

                // Update header
                $header = PaymentRequest::find($headerId);
                foreach($header->payment_requests_pi_details as $detail){
                    $ppn += $detail->purchase_invoice_header->ppn_amount;
                    $pph_23 += $detail->purchase_invoice_header->pph_amount;
                    $amount += $detail->purchase_invoice_header->total_price;
                    $total_amount += $detail->purchase_invoice_header->total_payment;
                }
                $header->amount = $amount;
                $header->total_amount = $total_amount;

                if($header->type == 'default'){
                    $header->ppn = $ppn;
                    $header->pph_23 = $pph_23;
                }
                else{
                    $header->ppn = 0;
                    $header->pph_23 = 0;
                }
                $header->save();
            }
            else{
                if(PaymentRequestsPoDetail::where('payment_requests_id', $headerId)->get()->count() == 1){
                    error_log("CHECK");
                    return Response::json(array('errors' => 'po_last'));
                }

                $detail = PaymentRequestsPoDetail::find($detailId);

                if(empty($detail)){
                    return Response::json(array('errors' => 'po_deleted'));
                }

                $detail->delete();

                // Update header
                $header = PaymentRequest::find($headerId);
                foreach($header->payment_requests_po_details as $detail){
                    $ppn += $detail->purchase_order_header->ppn_amount;
                    $pph_23 += $detail->purchase_order_header->pph_amount;
                    $amount += $detail->purchase_order_header->total_price;
                    $total_amount += $detail->purchase_order_header->total_payment;
                }
                $header->amount = $amount;
                $header->total_amount = $total_amount;

                if($header->type == 'default'){
                    $header->ppn = $ppn;
                    $header->pph_23 = $pph_23;
                }
                else{
                    $header->ppn = 0;
                    $header->pph_23 = 0;
                }
                $header->save();
            }

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}