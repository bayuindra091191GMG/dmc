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

        $createdDate = Carbon::parse($document->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($document->updated_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='documents/".$document->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'id'            => $document->id,
            'description'   => $document->description,
            'action'        => $action
        ];
    }
}