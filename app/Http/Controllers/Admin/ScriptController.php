<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Schedule;
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

    public function refreshExpiredMembers(){
        try {
            $schedules = Schedule::with(['course', 'customer'])
                ->whereIn('course_id', [1,2,3,4])
                ->where('status_id', 3)
                ->whereBetween('finish_date', array('2020-03-15 00:00:00', '2020-07-13 00:00:00'))
                ->get();

            //dd($schedules);

            $liveDate = Carbon::create(2020,7,13,0,0,0);
            foreach ($schedules as $schedule){
                $finishDate = Carbon::parse($schedule->finish_date);
                $diffInDays = $finishDate->diffInDays($liveDate);
                $newFinishDate = $liveDate->copy()->addDays($diffInDays);

                $schedule->finish_date = $newFinishDate->toDateTimeString();
                $schedule->save();

                //error_log($diffInDays. ' - '. $newFinishDate->toDateTimeString());
            }

            return 'SCRIPT SUCCESS!';
        }
        catch (\Exception $ex){
            dd($ex);
        }
    }
}