<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\CustomerPointHistory;
use App\Models\CustomerVoucher;
use App\Models\Voucher;
use App\Transformer\MasterData\VoucherTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            if($classes->count() > 0){
                Session::flash('errors', 'Voucher '. $voucher->name . ' Tidak dapat dihapus karena sudah pernah diredeem!');
                return Response::json(array('errors' => 'INVALID'));
            }
            $voucher->delete();

            Session::flash('message', 'Berhasil menghapus data Voucher '. $voucher->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            Log::error('Admin/VoucherController - destroy ERROR : '.$ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * Function to show buy voucher page.
    */
    public function buyPage(){
        return view('admin.vouchers.buy');
    }

    /**
     * Function to buy the Voucher.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buy(Request $request){
        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required|max:50',
            'voucher_id'    => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        //Buy Voucher
        $customer = Customer::find($request->input('customer_id'));
        $voucher = Voucher::find($request->input('voucher_id'));

        //Check Point
        if($customer->point < $voucher->point_needed){
            return redirect()->back()->withErrors('Point Student Tidak Cukup')->withInput($request->all());
        }

        CustomerVoucher::create([
            'customer_id'   => $customer->id,
            'voucher_id'    => $voucher->id,
            'created_at'    => Carbon::now('Asia/Jakarta'),
            'status_id'     => 1
        ]);

        $history = CustomerPointHistory::create([
            'customer_id'   => $customer->id,
            'point_from'    => $customer->point
        ]);

        $customer->point -= $voucher->point_needed;
        $customer->save();

        $history->point_min = $voucher->point_needed;
        $history->point_result = $customer->point;
        $history->voucher_id = $voucher->id;
        $history->notes = 'Beli Voucher '. $voucher->name;
        $history->save();

        Session::flash('message', 'Student ' . $customer->name . ' berhasil membeli voucher '. $voucher->name);
        return view('admin.vouchers.buy');
    }

    /**
     * Function to show Redeem Page.
    */
    public function redeemPage(){
        return view('admin.vouchers.redeem');
    }

    /**
     * Function to redeem the Voucher.
     * Can only Redeem Voucher with Type Tukar Barang.
     * For Discount Percentage, Discount Total, and Free Packages will be done in the Transaction page.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redeem(Request $request){
        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required|max:50',
            'voucher_id'    => 'required'
        ]);

        if($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $voucher = Voucher::find($request->input('voucher_id'));
        if($voucher->type != 'goods'){
            return redirect()->back()->withErrors('Fungsi ini hanya bisa untuk tukar barang!')->withInput($request->all());
        }

        $customer = Customer::find($request->input('customer_id'));
        $customerVoucher = CustomerVoucher::where('customer_id', $customer->id)->where('voucher_id', $voucher->id)->first();
        $customerVoucher->status_id = 8;
        $customerVoucher->save();

        Session::flash('message', 'Customer ' . $customer->name . ' Berhasil redeem voucher '. $voucher->name);
        return view('admin.vouchers.redeem');
    }

    public function getVouchers(Request $request){
        $term = trim($request->q);
        $vouchers = Voucher::where('name', 'LIKE', '%'. $term. '%')
            ->orderBy('name')
            ->get();

        $formatted_tags = [];

        foreach ($vouchers as $voucher) {
            $formatted_tags[] = ['id' => $voucher->id, 'text' => $voucher->name. ' - Point: '. $voucher->point_needed];
        }

        return Response::json($formatted_tags);
    }

    public function getGoodsVouchers(Request $request){
        $term = trim($request->q);
        $vouchers = Voucher::where('type', 'goods')->where('name', 'LIKE', '%'. $term. '%')
            ->orderBy('name')
            ->get();

        $formatted_tags = [];

        foreach ($vouchers as $voucher) {
            $formatted_tags[] = ['id' => $voucher->id, 'text' => $voucher->name. ' - Point: '. $voucher->point_needed];
        }

        return Response::json($formatted_tags);
    }

    public function checkVoucher(Request $request){
        try{
            //return Response::json(array('voucher' => $request->input('voucher_name')));
            if(!DB::table('vouchers')
                ->where('name', $request->input('voucher_name'))
                ->where('type', '!=', 'goods')
                ->exists()){
                return Response::json(array('errors' => 'INVALID1'));
            }
            else{
                $voucher = Voucher::where('name', $request->input('voucher_name'))->first();
                //return Response::json(array('result'=>$request->input('customer_id')));
                if(DB::table('customer_vouchers')
                    ->where('customer_id', $request->input('customer_id'))
                    ->where('voucher_id', $voucher->id)
                    ->where('status_id', 1)
                    ->exists()){
//                    $custVoucher = CustomerVoucher::where('customer_id', $request->input('customer_id'))
//                        ->where('voucher_id', $voucher->id)
//                        ->where('status_id', 1)
//                        ->first();

                    //return Response::json(array('result' => $voucher));
                    return Response::json([
                        'type'                  => $voucher->type,
                        'discount_total'        => $voucher->discount_total,
                        'discount_percentage'   => $voucher->discount_percentage,
                        'free_package'          => $voucher->free_package
                    ]);
                }
            }
        }
        catch (\Exception $ex){
            return Response::json(array('errors' => 'INVALID ' . $ex));
        }
    }
}
