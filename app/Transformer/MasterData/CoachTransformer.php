<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\Coach;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CoachTransformer extends TransformerAbstract
{
    public function transform(Coach $coach){

        $createdDate = Carbon::parse($coach->created_at)->format('d M Y');
        $name = "<a href='/admin/coaches/show/" . $coach->id. "' style='text-decoration: underline;'>". $coach->name. "</a>";
        $action = "<a class='btn btn-xs btn-primary' href='coaches/show/". $coach->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .=
            "<a class='btn btn-xs btn-info' href='coaches/".$coach->id."/edit' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $coach->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'name'              => $name,
            'email'             => $coach->email,
            'phone'             => $coach->phone,
            'address'           => $coach->address,
            'status'            => $coach->status->description,
            'created_at'        => $createdDate,
            'action'            => $action
        ];
    }
}
