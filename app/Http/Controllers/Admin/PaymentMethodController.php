<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\PaymentMethod;
use App\Models\Status;
use App\Transformer\MasterData\PaymentMethodTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.payment_methods.index');
    }

    //DataTables
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $payment_methods = PaymentMethod::all();
        return DataTables::of($payment_methods)
            ->setTransformer(new PaymentMethodTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|max:50',
            'fee' => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $payment_method = PaymentMethod::create([
            'description'          => $request->get('description'),
            'fee'          => $request->get('fee'),
            'status_id'          => 1
        ]);

//        return redirect()->intended(route('admin.payment_methods'));
//        return view('admin.payment_methods.create');

        Session::flash('message', 'Berhasil membuat data metode pembayaran baru!');

        return redirect()->route('admin.payment_methods.create');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function show(User $user)
//    {
//        return view('admin.users.show', ['user' => $user]);
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PaymentMethod $payment_method
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $payment_method)
    {

        return view('admin.payment_methods.edit', ['payment_method' => $payment_method]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param PaymentMethod $payment_method
     * @return mixed
     */
    public function update(Request $request, PaymentMethod $payment_method)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|max:50',
            'fee' => 'required|max:45',
            'status' => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $payment_method->description = $request->get('description');
        $payment_method->fee = $request->get('fee');
        $payment_method->status_id = $request->get('status');

        $payment_method->save();

//        return redirect()->intended(route('admin.payment_methods'));
//        return redirect()->intended(route('admin.payment_methods.edit', ['payment_method' => $payment_method]));

        Session::flash('message', 'Berhasil mengubah data metode pembayaran!');

        return redirect()->route('admin.payment_methods.edit', ['payment_method' => $payment_method]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
