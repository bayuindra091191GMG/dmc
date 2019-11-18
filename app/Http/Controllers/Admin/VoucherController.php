<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomerVoucher;
use App\Models\Voucher;
use App\Transformer\MasterData\VoucherTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courseType = "muaythai";
        $type = 1;

        $data = [
            'courseType'        => $courseType,
            'type'              => $type
        ];

        return view('admin.vouchers.index')->with($data);
    }

    /**
     * Function to Get the Data and send to DataTables.
    */
    public function anyData()
    {
        $vouchers = Voucher::where('id', '!=', 0)->get();
        return DataTables::of($vouchers)
            ->setTransformer(new VoucherTransformer())
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
        return view('admin.vouchers.create');
    }

    /**
     * Function to Store the new Voucher.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:50',
            'description'   => 'required',
            'point_needed'  => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        if($request->input('type') == 'discount_percentage'){
            if(empty($request->input('discount_percentage'))){
                return redirect()->back()->withErrors("Tipe Diskon Persen harus perlu isi field Diskon Persen!")->withInput($request->all());
            }
        }

        if($request->input('type') == 'discount_total'){
            if(empty($request->input('discount_total'))){
                return redirect()->back()->withErrors("Tipe Diskon Total harus perlu isi field Diskon Total!")->withInput($request->all());
            }
        }

        if($request->input('type') == 'free_package'){
            if(empty($request->input('free_package'))){
                return redirect()->back()->withErrors("Tipe Free Package harus perlu isi field Free Package!")->withInput($request->all());
            }
        }

        $user = Auth::user();

        $voucher = Voucher::create([
            'name'      => $request->input('name'),
            'description'   => $request->input('description'),
            'type'          => $request->input('type'),
            'point_needed'  => $request->input('point_needed'),
            'created_by'    => $user->id,
            'created_at'    => Carbon::now('Asia/Jakarta'),
            'status_id'     => 1
        ]);

        if($request->input('type') == 'discount_percentage'){
            $voucher->discount_percentage = $request->input('discount_percentage');
        }
        if($request->input('type') == 'discount_total'){
            $voucher->discount_total = $request->input('discount_total');
        }
        if($request->input('type') == 'free_package'){
            $voucher->free_package = $request->input('free_package');
        }
        $voucher->save();

        Session::flash('message', 'Berhasil membuat data Voucher baru!');
        return redirect()->route('admin.vouchers');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Voucher $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', ['voucher' => $voucher]);
    }

    /**
     * Function to update the Voucher data.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Voucher $voucher){
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:50',
            'description'   => 'required',
            'point_needed'  => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        if($request->input('type') == 'discount_percentage'){
            if(empty($request->input('discount_percentage'))){
                return redirect()->back()->withErrors("Tipe Diskon Persen harus perlu isi field Diskon Persen!")->withInput($request->all());
            }
        }

        if($request->input('type') == 'discount_total'){
            if(empty($request->input('discount_total'))){
                return redirect()->back()->withErrors("Tipe Diskon Total harus perlu isi field Diskon Total!")->withInput($request->all());
            }
        }

        if($request->input('type') == 'free_package'){
            if(empty($request->input('free_package'))){
                return redirect()->back()->withErrors("Tipe Free Package harus perlu isi field Free Package!")->withInput($request->all());
            }
        }

        $user = Auth::user();

        $voucher->type = $request->input('type');
        $voucher->name = $request->input('name');
        $voucher->description = $request->input('description');
        $voucher->point_needed = $request->input('point_needed');
        $voucher->updated_by = $user->id;
        $voucher->updated_at = Carbon::now('Asia/Jakarta');

        if($request->input('type') == 'discount_percentage'){
            $voucher->discount_percentage = $request->input('discount_percentage');
        }
        if($request->input('type') == 'discount_total'){
            $voucher->discount_total = $request->input('discount_total');
        }
        if($request->input('type') == 'free_package'){
            $voucher->free_package = $request->input('free_package');
        }
        $voucher->save();

        Session::flash('message', 'Berhasil Update data Voucher!');
        return redirect()->route('admin.vouchers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Request $request)
    {
        try{
            $voucher = Voucher::find($request->input('id'));

            //Check first if Voucher already in Transaction
            $classes = CustomerVoucher::where('voucher_id', $voucher->id)->get();
            if($classes != null){
                Session::flash('error', 'Data Trainer '. $voucher->name . ' Tidak dapat dihapus karena voucher sudah pernah diredeem!');
                return Response::json(array('success' => 'VALID'));
            }
            $voucher->delete();

            Session::flash('message', 'Berhasil menghapus data Trainer '. $voucher->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function redeemPage(){
        return view('admin.vouchers.redeem_page');
    }


    public function redeem(Request $request){

    }
}
