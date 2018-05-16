<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\Customer;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    public function transform(Customer $customer){

        $createdDate = Carbon::parse($customer->created_at)->format('d M Y');

        $action =
            "<a class='btn btn-xs btn-info' href='customers/".$customer->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $customer->id ."' ><i class='fa fa-trash'></i></a>";


        return[
            'name'              => $customer->name,
            'email'             => $customer->email ?? "-",
            'phone'             => $customer->phone,
            'age'               => $customer->age ?? "-",
            'parent_name'       => $customer->parent_name ?? "-",
            'address'           => $customer->address ?? "-",
            'status'            => $customer->status->description,
            'created_at'        => $createdDate,
            'action'            => $action
        ];
    }
}
