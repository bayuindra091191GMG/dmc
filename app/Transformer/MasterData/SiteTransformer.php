<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 3/11/2018
 * Time: 3:13 PM
 */

namespace App\Transformer\MasterData;


use App\Models\Site;
use League\Fractal\TransformerAbstract;

class SiteTransformer extends TransformerAbstract
{
    public function transform(Site $site){


        $action = "<a class='btn btn-xs btn-info' href='sites/".$site->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $site->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'              => $site->code,
            'name'              => $site->name,
            'location'          => $site->location ?? '-',
            'phone'             => $site->phone ?? '-',
            'pic'               => $site->pic ?? '-',
            'action'            => $action
        ];
    }
}