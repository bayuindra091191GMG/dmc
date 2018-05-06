<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 4/9/2018
 * Time: 10:18 AM
 */

namespace App\Transformer;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $notif){
        $user = Auth::user();

        $notification = "";
        if($notif->type === 'App\Notifications\MaterialRequestCreated'){
            if($notif->data['document_type'] === 'Material Request Part/Non-Part'){
                $route = route('admin.material_requests.other.show', ['material_request' => $notif->data['mr_id']]);
            }
            elseif($notif->data['document_type'] === 'Material Request BBM'){
                $route = route('admin.material_requests.fuel.show', ['material_request' => $notif->data['mr_id']]);
            }
            elseif($notif->data['document_type'] === 'Material Request Oli'){
                $route = route('admin.material_requests.fuel.show', ['material_request' => $notif->data['mr_id']]);
            }
            else{
                $route = route('admin.material_requests.service.show', ['material_request' => $notif->data['mr_id']]);
            }
            $notification .= "<span>MR </span><a style='text-decoration: underline;' href='". $route. "'>". $notif->data['code']. "</a> telah dibuat, mohon buat PR";
        }
        elseif($notif->type === 'App\Notifications\PurchaseRequestCreated'){
            if($user->roles->pluck('id')[0] === 13){
                $route = route('admin.purchase_requests.show', ['purchase_request' => $notif->data['pr_id']]);
                $notification .= "<span>PR </span><a style='text-decoration: underline;' href='". $route. "'>". $notif->data['code']. "</a> telah dibuat";
            }
            else{
                if($notif->data['receiver_is_creator'] === 'true'){
                    $mrType = 'default';
                    if($notif->data['mr_type'] === 1){
                        $mrType = 'other';
                    }
                    elseif($notif->data['mr_type'] === 2){
                        $mrType = 'fuel';
                    }
                    elseif($notif->data['mr_type'] === 3){
                        $mrType = 'oil';
                    }
                    else{
                        $mrType = 'service';
                    }
                    $mrRouteStr = 'admin.material_requests.'. $mrType. '.show';
                    $mrRoute = route($mrRouteStr, ['material_request' => $notif->data['mr_id']]);
                    $notification .= "<span>MR </span><a style='text-decoration: underline;' href='". $mrRoute. "'>". $notif->data['mr_code']. "</a> anda telah diproses ke PR";
                }
                else{
                    $route = route('admin.purchase_requests.show', ['purchase_request' => $notif->data['pr_id']]);
                    $notification .= "<span>PR </span><a style='text-decoration: underline;' href='". $route. "'>". $notif->data['code']. "</a> telah dibuat, mohon buat PO";
                }
            }
        }
        elseif($notif->type === 'App\Notifications\PurchaseOrderCreated'){
            if($user->roles->pluck('id')[0] === 13){
                $route = route('admin.purchase_orders.show', ['purchase_order' => $notif->data['po_id']]);
                $notification .= "<span>PO </span><a style='text-decoration: underline;' href='". $route. "'>". $notif->data['code']. "</a> telah dibuat";
            }
            else{
                if($notif->data['receiver_is_mr_creator'] === 'true'){
                    $mrType = 'default';
                    if($notif->data['mr_type'] === 1){
                        $mrType = 'other';
                    }
                    elseif($notif->data['mr_type'] === 2){
                        $mrType = 'fuel';
                    }
                    elseif($notif->data['mr_type'] === 3){
                        $mrType = 'oil';
                    }
                    else{
                        $mrType = 'service';
                    }
                    $mrRouteStr = 'admin.material_requests.'. $mrType. '.show';
                    $mrRoute = route($mrRouteStr, ['material_request' => $notif->data['mr_id']]);
                    $notification .= "<span>MR </span><a style='text-decoration: underline;' href='". $mrRoute. "'>". $notif->data['mr_code']. "</a> anda telah diproses ke PO";
                }
            }
        }

        return[
            'document'      => $notif->data['document_type'],
            'notification'  => $notification,
            'sender'        => $notif->data['sender_name']
        ];
    }
}