<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer\MasterData;


use App\Models\Auth\User\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user){

        try{
        $createdDate = Carbon::parse($user->created_at)->format('d M Y');

        $action = "<a class='btn btn-xs btn-info' href='users/".$user->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $user->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'          => $user->employee->code,
            'email'         => $user->email,
            'name'          => $user->employee->name,
            'department'    => $user->employee->department->name,
            'site'          => $user->employee->site->name,
            'role'          => $user->roles->pluck('name')->implode(','),
            'status'        => $user->status->description,
            'created_at'    => $createdDate,
            'action'        => $action
        ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}