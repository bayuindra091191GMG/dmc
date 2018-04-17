<?php

namespace App\Libs;

use Carbon\Carbon;

class Utilities
{
    public static function GenerateNumber($code, $nextNumber)
    {
        $modulus = "";
        $now = Carbon::now('Asia/Jakarta');

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

        $number = $code."/".$now->year."/".$now->month."/".$modulus.$nextNumber;
        return $number;
    }

    public static function GenerateNumberPurchaseOrder($code, $nextNumber)
    {
        $modulus = "";
        $now = Carbon::now('Asia/Jakarta');

        $mod = strlen($nextNumber);
        switch ($mod){
            case 1:
                $modulus = "000000";
                break;
            case 2:
                $modulus = "00000";
                break;
            case 3:
                $modulus = "0000";
                break;
            case 4:
                $modulus = "000";
                break;
            case 5:
                $modulus = "00";
                break;
            case 6:
                $modulus = "0";
                break;
        }

        $number = $code."/".$now->year."/".$modulus.$nextNumber;
        return $number;
    }

    public static function arrayIsUnique($array){
        return array_unique($array) == $array;
    }
}