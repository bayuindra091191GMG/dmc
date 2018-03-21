<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\ApprovalRule;
use App\Models\Supplier;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class SupplierTransformer extends TransformerAbstract
{
    protected $mode = 'default';

    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function transform(Supplier $supplier){

        $createdDate = Carbon::parse($supplier->created_at)->format('d M Y');
        $route = route('admin.payment_requests.before_create_pi', ['supplier' => $supplier->id]);

        if($this->mode === 'default'){
            $action =
                "<a class='btn btn-xs btn-info' href='suppliers/".$supplier->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $supplier->id ."' ><i class='fa fa-trash'></i></a>";
        }else{
            $action = "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Pilih Vendor </a>";
        }

        return[
            'code'              => $supplier->code,
            'name'              => $supplier->name,
            'email'             => $supplier->email ?? "-",
            'phone'             => $supplier->phone,
            'contact_person'    => $supplier->contact_person ?? "-",
            'city'              => $supplier->city ?? "-",
            'created_at'        => $createdDate,
            'action'            => $action
        ];
    }
}