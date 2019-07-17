<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\CourseDetail;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        /*
         * Remove the socialite session variable if exists
         */

        \Session::forget(config('access.socialite_session_name'));

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/admin');
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => 'Email atau password anda salah!'];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        try{

            $errors = [];

//            if (config('auth.users.confirm_email') && !$user->confirmed) {
//                $errors = [$this->username() => __('auth.notconfirmed', ['url' => route('confirm.send', [$user->email])])];
//            }

            if (!$user->active) {
                $errors = [$this->username() => __('auth.active')];
            }

            if ($user->status_id == 2){
                $errors = [$this->username() => 'Email atau password anda salah!'];
            }

            if ($errors) {
                auth()->logout();  //logout

                return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors($errors);
            }

            if($request->input('redirect') !== 'default'){
                $url = $request->input('redirect');
                return redirect($url);
            }

            return redirect('/admin');
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function showLoginForm(){

        $redirect = "default";
        if(!empty(\request()->redirect)){
            $redirect = \request()->redirect;
        }

        return view('auth.login', compact('redirect'));
    }

    public function mulitpleStudent(){

        try {
            DB::transaction(function () {
                //ubah student di schedule
                $items = Schedule::whereIn('customer_id', [327, 405, 237, 197, 225, 143, 301])->get();
                foreach ($items as $item1) {
                    if($item1->customer_id == 327){
                        $item1->customer_id = 354;
                        $item1->save();
                    }
                    if($item1->customer_id == 237){
                        $item1->customer_id = 200;
                        $item1->save();
                    }
                    if($item1->customer_id == 197){
                        $item1->customer_id = 34;
                        $item1->save();
                    }
                    if($item1->customer_id == 225){
                        $item1->customer_id = 49;
                        $item1->save();
                    }
                    if($item1->customer_id == 143){
                        $item1->customer_id = 51;
                        $item1->save();
                    }
                    if($item1->customer_id == 301){
                        $item1->customer_id = 444;
                        $item1->save();
                    }
                    if($item1->customer_id == 405){
                        $item1->customer_id = 406;
                        $item1->save();
                    }
                }
                //ganti attendance
                $items2 = Attendance::whereIn('customer_id', [327, 405, 237, 197, 225, 143, 301])->get();
                foreach ($items2 as $item2) {
                    if($item2->customer_id == 327){
                        $item2->customer_id = 354;
                        $item2->save();
                    }
                    if($item2->customer_id == 237){
                        $item2->customer_id = 200;
                        $item2->save();
                    }
                    if($item2->customer_id == 197){
                        $item2->customer_id = 34;
                        $item2->save();
                    }
                    if($item2->customer_id == 225){
                        $item2->customer_id = 49;
                        $item2->save();
                    }
                    if($item2->customer_id == 143){
                        $item2->customer_id = 51;
                        $item2->save();
                    }
                    if($item2->customer_id == 301){
                        $item2->customer_id = 444;
                        $item2->save();
                    }
                    if($item2->customer_id == 405){
                        $item2->customer_id = 406;
                        $item2->save();
                    }
                }
                //ganti transaction_header
                $items3 = TransactionHeader::whereIn('customer_id', [327, 405, 237, 197, 225, 143, 301])->get();
                foreach ($items3 as $item3) {
                    if($item3->customer_id == 327){
                        $item3->customer_id = 354;
                        $item3->save();
                    }
                    if($item3->customer_id == 237){
                        $item3->customer_id = 200;
                        $item3->save();
                    }
                    if($item3->customer_id == 197){
                        $item3->customer_id = 34;
                        $item3->save();
                    }
                    if($item3->customer_id == 225){
                        $item3->customer_id = 49;
                        $item3->save();
                    }
                    if($item3->customer_id == 143){
                        $item3->customer_id = 51;
                        $item3->save();
                    }
                    if($item3->customer_id == 301){
                        $item3->customer_id = 444;
                        $item3->save();
                    }
                    if($item3->customer_id == 405){
                        $item3->customer_id = 406;
                        $item3->save();
                    }
                }
                //delete student yg double2
                $itemsz = Customer::whereIn('id', [327, 405, 237, 197, 225, 143, 301])->get();
//                dd($itemsz);
                foreach ($itemsz as $item) {
//                    dd($item);
                    $item->delete();
                }
            });
            return "success";
        }
        catch(\Exception $ex){
            error_log($ex);
            return "something went wrong. ". $ex;
        }
    }

    public function test(){

        try {
            DB::transaction(function () {

                //scfipt for change class, coach in class
                //=====================================================================================================
                $items = Schedule::whereIn('course_id', [6, 7, 9, 12, 14, 16, 17, 19, 22, 24, 29])->get();
                //ubah course di schedule menjadi 1 kelas aja
                foreach ($items as $item) {
                    if($item->course_id == 6){
                        $item->course_id = 5;
                        $item->save();
                    }
                    if($item->course_id == 7){
                        $item->course_id = 8;
                        $item->save();
                    }
                    if($item->course_id == 9){
                        $item->course_id = 10;
                        $item->save();
                    }
                    if($item->course_id == 12){
                        $item->course_id = 11;
                        $item->save();
                    }
                    if($item->course_id == 14){
                        $item->course_id = 13;
                        $item->save();
                    }
                    if($item->course_id == 16){
                        $item->course_id = 15;
                        $item->save();
                    }
                    if($item->course_id == 17){
                        $item->course_id = 18;
                        $item->save();
                    }
                    if($item->course_id == 19){
                        $item->course_id = 21;
                        $item->save();
                    }
                    if($item->course_id == 22){
                        $item->course_id = 23;
                        $item->save();
                    }
                    if($item->course_id == 24){
                        $item->course_id = 25;
                        $item->save();
                    }
                    if($item->course_id == 29){
                        $item->course_id = 30;
                        $item->save();
                    }
                }
                //ubah status course
                $courses = Course::whereIn('id', [6, 7, 9, 12, 14, 16, 17, 19, 22, 24, 29])->get();
                foreach($courses as $course){
                    $course->status_id = 2;
                    $course->save();
                }

                //ubah coach melisa menjadi melisa/han
                $coach = Coach::find(5);
                $coach->name = "Melisa / Han";
                $coach->save();
                //=====================================================================================================

//                $now = Carbon::parse('2018-11-30 15:03:58');
//                $startDate = Carbon::parse('2018-11-01 15:03:58');
//                $items = Schedule::whereHas('course', function ($query) {
//                    $query->where('type', 4);
//                })
//                    ->whereDate('finish_date', '<', $startDate)
//                    ->where(function ($q) {
//                        $q->where('customer_id', 283)
//                            ->orWhere('customer_id', 78)
//                            ->orWhere('customer_id', 171)
//                            ->orWhere('customer_id', 105)
//                            ->orWhere('customer_id', 158)
//                            ->orWhere('customer_id', 1)
//                            ->orWhere('customer_id', 144)
//                            ->orWhere('customer_id', 294)
//                            ->orWhere('customer_id', 97)
//                            ->orWhere('customer_id', 177)
//                            ->orWhere('customer_id', 34)
//                            ->orWhere('customer_id', 13)
//                            ->orWhere('customer_id', 25)
//                            ->orWhere('customer_id', 53)
//                            ->orWhere('customer_id', 190)
//                            ->orWhere('customer_id', 52)
//                            ->orWhere('customer_id', 44)
//                            ->orWhere('customer_id', 185)
//                            ->orWhere('customer_id', 162)
//                            ->orWhere('customer_id', 101)
//                            ->orWhere('customer_id', 252)
//                            ->orWhere('customer_id', 168)
//                            ->orWhere('customer_id', 197)
//                            ->orWhere('customer_id', 84)
//                            ->orWhere('customer_id', 295)
//                            ->orWhere('customer_id', 160)
//                            ->orWhere('customer_id', 99)
//                            ->orWhere('customer_id', 258)
//                            ->orWhere('customer_id', 45)
//                            ->orWhere('customer_id', 146)
//                            ->orWhere('customer_id', 104)
//                            ->orWhere('customer_id', 274)
//                            ->orWhere('customer_id', 58)
//                            ->orWhere('customer_id', 257)
//                            ->orWhere('customer_id', 167)
//                            ->orWhere('customer_id', 299)
//                            ->orWhere('customer_id', 145)
//                            ->orWhere('customer_id', 169)
//                            ->orWhere('customer_id', 183)
//                            ->orWhere('customer_id', 24)
//                            ->orWhere('customer_id', 211)
//                            ->orWhere('customer_id', 76)
//                            ->orWhere('customer_id', 289)
//                            ->orWhere('customer_id', 19)
//                            ->orWhere('customer_id', 159)
//                            ->orWhere('customer_id', 43)
//                            ->orWhere('customer_id', 3)
//                            ->orWhere('customer_id', 56)
//                            ->orWhere('customer_id', 98)
//                            ->orWhere('customer_id', 305)
//                            ->orWhere('customer_id', 286)
//                            ->orWhere('customer_id', 100)
//                            ->orWhere('customer_id', 290)
//                            ->orWhere('customer_id', 79)
//                            ->orWhere('customer_id', 193)
//                            ->orWhere('customer_id', 194)
//                            ->orWhere('customer_id', 26)
//                            ->orWhere('customer_id', 170)
//                            ->orWhere('customer_id', 253)
//                            ->orWhere('customer_id', 127)
//                            ->orWhere('customer_id', 4)
//                            ->orWhere('customer_id', 23)
//                            ->orWhere('customer_id', 77)
//                            ->orWhere('customer_id', 297);
//                    })
//                    ->orderby('finish_date', 'desc')
//                    ->get();
//                dd($items);
//                $count = 1;
//                foreach ($items as $item) {
//                    $month = Carbon::parse($item->finish_date)->month;
////                    dd($month);
//                    for($i=$month; $i < 10; $i++){
//                        $trxHeader = TransactionHeader::create([
//                            'code' => 'TRN/MAN/2018/11/' . $count,
//                            'type' => 1,
//                            'customer_id' => $item->customer_id,
//                            'date' => $now->toDateTimeString(),
//                            'payment_method' => 'TUNAI',
//                            'invoice_number' => 'INV/MAN/2018/11/' . $count,
//                            'registration_fee' => 0,
//                            'total_price' => $item->course->price,
//                            'total_payment' => $item->course->price,
//                            'status_id' => 1,
//                            'created_by' => 1,
//                            'created_at' => $now->toDateTimeString(),
//                            'updated_by' => 1,
//                            'updated_at' => $now->toDateTimeString()
//                        ]);
//
//                        $trxDetail = TransactionDetail::create([
//                            'header_id' => $trxHeader->id,
//                            'schedule_id' => $item->id,
//                            'day' => $item->day,
//                            'meeting_attendeds' => 0,
//                            'price' => $item->course->price
//                        ]);
//                        $trxDetail->save();
//
//                        $item->finish_date = $now->toDateTimeString();
//                        $item->save();
//
//                        $count++;
//                    }
//                }
            });
            return "success";
        }
        catch(\Exception $ex){
                error_log($ex);
            return "something went wrong. ". $ex;
        }
    }

    public function courseScript(){
        try{
            $courses = Course::all();

            foreach ($courses as $course){
                foreach ($course->days as $day){
                    foreach ($day->hours as $hour){
                        $dayString = $day->day_string;

                        if($dayString === 'Senin'){
                            $dayNumber = 1;
                        }
                        else if($dayString === 'Selasa'){
                            $dayNumber = 2;
                        }
                        else if($dayString === 'Rabu'){
                            $dayNumber = 3;
                        }
                        else if($dayString === 'Kamis'){
                            $dayNumber = 4;
                        }
                        else if($dayString === 'Jumat'){
                            $dayNumber = 5;
                        }
                        else if($dayString === 'Sabtu'){
                            $dayNumber = 6;
                        }
                        else{
                            $dayNumber = 7;
                        }

                        //error_log($hour->hour_string. ':00');

                        CourseDetail::create([
                            'course_id'         => $course->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hour->hour_string,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);
                    }
                }
            }

            return 'SUCCESS!';
        }
        catch(\Exception $ex){
            return $ex;
        }

    }

    public function countStudent(){
        try{
            $courseDetails = CourseDetail::all();

            $schedules = Schedule::whereIn('status_id', [1,3,7])
                ->whereHas('course', function ($query){
                    $query->where('type', '!=', 1)
                        ->where('status_id', 1);
                })
                ->get();

            foreach ($courseDetails as $detail){
                $totalStudent = 0;
                foreach ($schedules as $schedule){
                    $splitted = explode('-', $schedule->day);
                    $dayString = trim($splitted[0]);
                    $timeString = trim($splitted[1]);

                    if($detail->course_id === $schedule->course_id &&
                        $detail->day_name === $dayString &&
                        $detail->time === $timeString){
                        $totalStudent++;
                    }
                }

                $detail->current_capacity = $totalStudent;
                $detail->save();
            }

            return 'SUCCESS';
        }
        catch (\Exception $ex){
            return $ex;
        }
    }
}
