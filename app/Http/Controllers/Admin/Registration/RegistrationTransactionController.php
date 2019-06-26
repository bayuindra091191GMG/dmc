<?php


namespace App\Http\Controllers\Admin\Registration;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Customer;
use App\Models\NumberingSystem;
use App\Models\Schedule;
use Illuminate\Support\Carbon;

class RegistrationTransactionController extends Controller
{
    public function formStepThree(int $type, int $student_id){
        if($type === 1){
            $courseType = 'MUAYTHAI';
            $backRoute = 'admin.registration.muaythai.step-one';
        }
        else if($type === 2){
            $courseType = 'DANCE';
            $backRoute = 'admin.registration.dance.step-one';
        }
        else if($type === 3){
            $courseType = 'PRIVATE';
            $backRoute = 'admin.registration.private.step-one';
        }
        else if($type === 4){
            $courseType = 'GYMNASTIC';
            $backRoute = 'admin.registration.gymnastic.step-one';
        }
        else{
            $courseType = 'INVALID';
            $viewPath = 'INVALID';
            $backRoute = 'INVALID';
            dd('INVALID COURSE TYPE!');
        }

        $student = Customer::find($student_id);
        if(empty($student)){
            dd('INVALID STUDENT!');
        }

        // Numbering System
        $sysNo = NumberingSystem::find(2);
        $autoNumber = Utilities::GenerateNumberPurchaseOrder($sysNo->document, $sysNo->next_no);

        $today = Carbon::today('Asia/Jakarta')->format('d M y');

        // Get not active schedule
        $schedules = Schedule::where('customer_id', $student_id)
            ->whereHas('course', function($query) use ($type){
                $query->where('type', $type);
            })
            ->where('status_id', 2)
            ->get();

        $data = [
            'type'              => $type,
            'courseType'        => $courseType,
            'student'           => $student,
            'autoNumber'        => $autoNumber,
            'today'             => $today,
            'schedules'         => $schedules
        ];

        return view('admin.registrations.transactions.step_3_transaction')->with($data);
    }
}