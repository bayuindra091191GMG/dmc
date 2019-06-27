<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\User\User;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\TransactionHeader;
use App\Transformer\MasterData\CustomerTransformer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.customers.index');
    }


    //DataTables

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function anyData()
    {
        $customers = Customer::all();
        return DataTables::of($customers)
            ->setTransformer(new CustomerTransformer())
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
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'email'             => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $dob = null;
        if(!empty($request->input('dob'))){
            $dob = Carbon::createFromFormat('d M Y', $request->input('dob'), 'Asia/Jakarta');
        }

        Customer::create([
            'name'          => $request->get('name'),
            'email'         => $request->get('email'),
            'phone'         => $request->get('phone') ?? null,
            'address'       => $request->get('address') ?? null,
            'age'           => $request->get('age') ?? null,
            'dob'           => $dob !== null ? $dob->toDateTimeString() : null,
            'parent_name'   => $request->get('parent_name') ?? null
        ]);

        Session::flash('message', 'Berhasil membuat data Student baru!');

        return redirect()->route('admin.customers');
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return Factory|View
     */
    public function show(Customer $customer)
    {
        $schedules = Schedule::where('customer_id', $customer->id)->get();

        $dob = '-';
        if(!empty($customer->dob)){
            $dob = Carbon::parse($customer->dob)->format('d M Y');
        }

        $data = [
            'customer'      => $customer,
            'schedules'     => $schedules,
            'dob'           => $dob
        ];

        return view('admin.customers.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', ['customer' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @return mixed
     */
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'email'             => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $dob = null;
        if(!empty($request->input('dob'))){
            $dob = Carbon::createFromFormat('d M Y', $request->input('dob'), 'Asia/Jakarta');
        }

        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->age = $request->input('age') ?? null;
        $customer->dob = $dob !== null ? $dob->toDateTimeString() : null;
        $customer->parent_name = $request->input('parent_name') ?? null;
        $customer->address = $request->input('address') ?? null;
        $customer->phone = $request->input('phone') ?? null;
        $customer->updated_at = $dateTimeNow;
        $customer->save();

        Session::flash('message', 'Berhasil mengubah data Student!');

        return redirect()->route('admin.customers.edit', ['customer' => $customer]);
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
            $customer = Customer::find($request->input('id'));

            //Check first if User already in Transaction
            $transaction = TransactionHeader::where('customer_id', $request->input('id'))->get();
            if($transaction->count() > 0){
                Session::flash('error', 'Data Customer '. $customer->name . ' Tidak dapat dihapus karena masih memiliki Kelas atau Paket!');
                return Response::json(array('errors' => 'INVALID'));
            }
            $customer->delete();

            Session::flash('message', 'Berhasil menghapus data Customer '. $customer->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function getCustomers(Request $request){
        $term = trim($request->q);
        $customers= Customer::where('name', 'LIKE', '%'. $term. '%')
            ->orderBy('name')
            ->get();

        $formatted_tags = [];

        foreach ($customers as $customer) {
            $parentName = 'Tidak Ada Data Ortu';
            if(!empty($customer->parent_name)){
                $parentName = $customer->parent_name;
            }

            $formatted_tags[] = ['id' => $customer->id, 'text' => $customer->name. ' - '. $parentName];
        }

        return \Response::json($formatted_tags);
    }

    public function getCustomerAttendances(Request $request){
        $term = trim($request->q);
        $customers= Customer::where('name', 'LIKE', '%'. $term. '%')
            ->orwhere('phone', 'LIKE', '%'. $term. '%')
            ->orwhere('email', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($customers as $customer) {
            $formatted_tags[] = ['id' => $customer->id, 'text' => $customer->name. ' - '. $customer->email. ' - '. $customer->phone];
        }

        return \Response::json($formatted_tags);
    }
}
