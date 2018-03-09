<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Document;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DocumentTransformer extends TransformerAbstract
{
    public function transform(Document $document){
        $action = "<a class='btn btn-xs btn-info' href='documents/".$document->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $document->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'          => $document->code,
            'description'   => $document->description,
            'action'        => $action
        ];
    }
}