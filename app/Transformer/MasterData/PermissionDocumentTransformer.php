<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\PermissionDocument;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PermissionDocumentTransformer extends TransformerAbstract
{
    public function transform(PermissionDocument $permissionDocument){

        $createdDate = Carbon::parse($permissionDocument->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($permissionDocument->updated_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='permission_documents/".$permissionDocument->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        if($permissionDocument->read == 1){
            $read = 'Ya';
        }
        else{
            $read = 'Tidak';
        }
        if($permissionDocument->create == 1){
            $create = "Ya";
        }
        else{
            $create =  "Tidak";
        }
        if($permissionDocument->update == 1){
            $update = "Ya";
        }
        else{
            $update = "Tidak";
        }
        if($permissionDocument->print == 1){
            $print = "Ya";
        }
        else{
            $print = "Tidak";
        }
        if($permissionDocument->delete == 1){
            $delete = "Ya";
        }
        else{
            $delete = "Tidak";
        }

        return[
            'role'          => $permissionDocument->role->name,
            'document'      => $permissionDocument->document->description,
            'read'          => $read,
            'create'        => $create,
            'update'        => $update,
            'print'         => $print,
            'delete'        => $delete,
            'created_by'    => $permissionDocument->createdBy->email,
            'created_at'    => $createdDate,
            'updated_by'    => $permissionDocument->updatedBy->email,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}