<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Auth\User\User;
use App\Models\PreferenceCompany;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function edit(){
//        if(Auth::guard('auth')->user()->id != $id){
//            return Redirect::route('admin-list');
//        }

        return View('admin.settings.edit-user');
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'current-password'      => 'required',
            'password'              => 'required|min:6|max:20|same:password',
            'password-confirmation' => 'required|same:password'
        ],[
            'password-confirmation.same'    => 'Password dan konfirmasi password harus sama!'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        $user = User::find($id);

        $user->password = bcrypt($request->input('password'));
        $user->save();

        Session::flash('message', 'Berhasil mengganti data password!');

        return redirect()->route('admin.dashboard');
    }

    public function preference(){
        $preference = PreferenceCompany::find(1);

        return View('admin.settings.edit-preference', compact('preference'));
    }

    public function preferenceUpdate(Request $request, PreferenceCompany $preference){
        $validator = Validator::make($request->all(), [
            'address' => 'required|max:255',
            'phone'     => 'required|max:45',
            'fax' => 'required|max:45',
            'email' => 'required|email|max:255',
            'ppn' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());


        $preference->address =$request->input('address');
        $preference->phone = $request->input('phone');
        $preference->fax = $request->input('fax');
        $preference->email = $request->input('email');
        $preference->ppn = $request->input('ppn');
        $preference->approval_setting = $request->input('approval_setting');
        $preference->save();

        Session::flash('message', 'Berhasil mengganti data preferensi perusahaan!');

        return redirect()->route('admin.settings.preference');
    }

    public function expiredDays(){
        return View('admin.settings.edit-expired-days');
    }

    public function addMoreExpiredDays(Request $request){
        $validator = Validator::make($request->all(),[
            'days'  => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Add days to all the Package
        $packages = Schedule::whereHas('course', function($query){
            $query->where('type', 1)->where('status_id', 3);
        })->get();

        foreach ($packages as $package){
            $temp = Carbon::parse($package->finish_date);
            $temp->addDays($request->input('days'));
            $package->finish_date = $temp->toDateTimeString();
            $package->save();
        }

        Session::flash('message', 'Berhasil menambah hari expired untuk paket!');

        return redirect()->route('admin.settings.edit_expired_days');
    }
}
