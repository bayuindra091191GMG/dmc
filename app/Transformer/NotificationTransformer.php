<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 4/9/2018
 * Time: 10:18 AM
 */

namespace App\Transformer;

use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $notif){

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
                $route = route('admin.material_requests.sergice.show', ['material_request' => $notif->data['mr_id']]);
            }
            $notification .= "<span>MR </span><a style='text-decoration: underline;' href='". $route. "'>". $notif->data['code']. "</a>" ;
        }

        return[
            'document'      => $notif->data['document'],
            'notification'  => $notification,
            'sender'        => $notif->data['sender_name']
        ];
    }
}