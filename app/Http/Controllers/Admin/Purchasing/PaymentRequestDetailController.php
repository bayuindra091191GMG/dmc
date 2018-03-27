<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 27/03/2018
 * Time: 10:35
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\PaymentRequestsPiDetail;
use App\Models\PaymentRequestsPoDetail;
use App\Models\PurchaseInvoiceHeader;
use App\Models\PurchaseOrderHeader;
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
            error_log($ex);
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
            if($type == 'PI'){
                if(!$request->filled('pi_id')){
                    return Response::json(array('errors' => 'pi_required'));
                }

                $piId = $request->input('pi_id');

                $rfpPiDetail = PaymentRequestsPiDetail::where('payment_request_id', $headerId)
                    ->where('purchase_invoice_header_id', $piId)
                    ->first();

                if(empty($rfpPiDetail)){
                    return Response::json(array('errors' => 'pi_deleted'));
                }

                $rfpPiDetail->purchase_invoice_header_id = $piId;
                $rfpPiDetail->save();

                $json = PurchaseInvoiceHeader::find($piId);
            }
            else{
                if(!$request->filled('po_id')){
                    return Response::json(array('errors' => 'po_required'));
                }

                $poId = $request->input('po_id');

                $rfpPoDetail = PaymentRequestsPiDetail::where('payment_request_id', $headerId)
                    ->where('purchase_order_id', $poId)
                    ->first();

                if(empty($rfpPoDetail)){
                    return Response::json(array('errors' => 'po_deleted'));
                }

                $rfpPoDetail->purchase_order_id = $poId;
                $rfpPoDetail->save();

                $json = PurchaseOrderHeader::find($poId);
            }

            return new JsonResponse($json);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }

    public function delete(Request $request){
        try{

            $detail = PurchaseRequestDetail::find(Input::get('id'));

            // Validate detail count
            $details = PurchaseRequestDetail::where('header_id', $detail->header_id)->get();
            if($details->count() == 1){
                return Response::json(array('errors' => 'INVALID'));
            }

            $detail->delete();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}