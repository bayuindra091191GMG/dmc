<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Site;
use App\Models\Supplier;
use App\Transformer\MasterData\EmployeeTransformer;
use App\Transformer\MasterData\SupplierTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'code'      => 'required',
            'email'     => 'nullable|email|max:45',
            'phone'     => 'max:45',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        $supplier = Supplier::create([
            'name'          => $request->get('name'),
            'code'          => $request->get('code'),
            'email'         => $request->get('email'),
            'phone'         => $request->get('phone'),
            'address'         => $request->get('address'),
            'created_by'    => $user->id,
            'created_at'    => $dateTimeNow->toDateTimeString(),
            'updated_by'    => $user->id,
            'updated_at'    => $dateTimeNow->toDateTimeString(),
        ]);

        if(!empty(Input::get('contract_start'))){
            $contractStart = Carbon::createFromFormat('d M Y', Input::get('contract_start'), 'Asia/Jakarta');
            $supplier->contract_start_date = $contractStart->toDateString();
            $supplier->save();
        }
        if(!empty(Input::get('contract_finish'))){
            $contractFinish = Carbon::createFromFormat('d M Y', Input::get('contract_finish'), 'Asia/Jakarta');
            $supplier->contract_finish_date = $contractFinish->toDateString();
            $supplier->save();
        }

        Session::flash('message', 'berhasil membuat data vendor baru!');

        return redirect(route('admin.suppliers'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        $contractStart = Carbon::parse($supplier->contract_start_date)->format('d M Y');
        $contractFinish = Carbon::parse($supplier->contract_finish_date)->format('d M Y');
        return view('admin.suppliers.edit', compact('supplier', 'contractStart', 'contractFinish'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'code'      => 'required',
            'email'     => 'email|max:45',
            'phone'     => 'max:45',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        $supplier->name = $request->get('name');
        $supplier->code = $request->get('code');
        $supplier->email = $request->get('email');
        $supplier->phone = $request->get('phone');
        $supplier->address = $request->get('address');
        $supplier->updated_by = $user->id;
        $supplier->updated_at = $dateTimeNow->toDateTimeString();
        $supplier->save();

        if(!empty(Input::get('contract_start'))){
            $contractStart = Carbon::createFromFormat('d M Y', Input::get('contract_start'), 'Asia/Jakarta');
            $supplier->contract_start_date = $contractStart->toDateString();
            $supplier->save();
        }
        if(!empty(Input::get('contract_finish'))){
            $contractFinish = Carbon::createFromFormat('d M Y', Input::get('contract_finish'), 'Asia/Jakarta');
            $supplier->contract_finish_date = $contractFinish->toDateString();
            $supplier->save();
        }

        Session::flash('message', 'Berhasil mengubah data vendor!');

        return redirect(route('admin.suppliers.edit', ['supplier' => $supplier->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getIndex()
    {
        $suppliers = Supplier::all();
        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->setTransformer(new SupplierTransformer())
            ->make(true);
    }

    public function getSuppliers(Request $request){
        $term = trim($request->q);
        $vendors = Supplier::where('code', 'LIKE', '%'. $term. '%')
            ->orWhere('name', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($vendors as $vendor) {
            $formatted_tags[] = ['id' => $vendor->id, 'text' => $vendor->name];
        }

        return \Response::json($formatted_tags);
    }
}
