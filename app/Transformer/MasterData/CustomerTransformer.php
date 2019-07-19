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

        $createdDate = Carbon::parse($customer->created_at)->toIso8601String();
        $name = "<a href='/admin/customers/show/" . $customer->id. "' style='text-decoration: underline;'>". $customer->name. "</a>";
        $action = "<a class='btn btn-xs btn-primary' href='customers/show/". $customer->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .=
            "<a class='btn btn-xs btn-info' href='customers/".$customer->id."/edit' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $customer->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'name'              => $name,
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
