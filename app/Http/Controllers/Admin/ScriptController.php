<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;

class ScriptController extends Controller
{
    public function generateMemberId(){
        $members = Customer::orderBy('created_at')->get();

        $dmcCode = '2501';
        $index = 1;
        foreach ($members as $member){
            $member->member_id = $this->generateMemberNumber($dmcCode, $index);
            $member->save();

            $index++;
        }

        return 'SCRIPT SUCCESS!';
    }

    public function generateMemberNumber($code, $nextNumber)
    {
        $modulus = "";

        $mod = strlen($nextNumber);
        switch ($mod){
            case 1:
                $modulus = "000";
                break;
            case 2:
                $modulus = "00";
                break;
            case 3:
                $modulus = "0";
                break;
        }

        $number = $code. $modulus. $nextNumber;
        return $number;
    }
}