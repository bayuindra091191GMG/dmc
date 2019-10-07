<?php

namespace App\Http\Controllers\Admin;

use App\Libs\Utilities;
use App\Models\Attendance;
use App\Models\Auth\User\User;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\TransactionHeader;
use App\Transformer\MasterData\CustomerTransformer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
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
        $prepend = '2501';
        $nextNo = Utilities::GetNextMemberNumber($prepend);
        $memberId = Utilities::GenerateMemberNumber($prepend, $nextNo);

        $data = [
            'memberId'      => $memberId
        ];

        return view('admin.customers.create')->with($data);
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

        // Generate member ID
        $prepend = '2501';
        $nextNo = Utilities::GetNextMemberNumber($prepend);
        $memberId = Utilities::GenerateMemberNumber($prepend, $nextNo);

        $dob = null;
        if(!empty($request->input('dob'))){
            $dob = Carbon::createFromFormat('d M Y', $request->input('dob'), 'Asia/Jakarta');
        }

        Customer::create([
            'member_id'     => $memberId,
            'barcode'       => $request->input('barcode') ?? null,
            'name'          => $request->get('name'),
            'email'         => $request->get('email'),
            'phone'         => $request->get('phone') ?? null,
            'address'       => $request->get('address') ?? null,
            'age'           => $request->get('age') ?? null,
            'dob'           => $dob !== null ? $dob->toDateTimeString() : null,
            'parent_name'   => $request->get('parent_name') ?? null
        ]);

        // Update member autonumber
        Utilities::UpdateMemberNumber($prepend);

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

//        $customer->member_id = $request->input('member_id') ?? null;
        $customer->barcode = $request->input('barcode') ?? null;
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->age = $request->input('age') ?? null;
        $customer->dob = $dob !== null ? $dob->toDateTimeString() : null;
        $customer->parent_name = $request->input('parent_name') ?? null;
        $customer->address = $request->input('address') ?? null;
        $customer->phone = $request->input('phone') ?? null;
        $customer->updated_at = $dateTimeNow;

        if($request->filled('photo')){
            $image = str_replace('data:image/jpeg;base64,', '', $request->input('photo'));
            $image = str_replace(' ', '+', $image);
            $imageName = $customer->name.'_photo.jpg';
            \File::put(public_path(). '/storage/students/' . $imageName, base64_decode($image));

            $customer->photo_path = $imageName;
        }

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

    public function getStudentScanResult(Request $request){
        try{
            $barcode = $request->input('barcode');
            error_log($barcode);
            $student = Customer::where('barcode', $barcode)->first();

            if(empty($student)){
                return Response::json(array('errors' => 'INVALID'));
            }

            $schedules = Schedule::where('customer_id', $student->id)
                ->where('status_id', 3)
                ->whereHas('course', function($query){
                    $query->whereIn('type', [1,2]);
                })
                ->get();

            $scheduleJsons = collect();
            foreach ($schedules as $schedule){
                $scheduleJson = collect([
                    'schedule_id'       => $schedule->id,
                    'course_name'       => $schedule->course->name,
                    'coach'             => $schedule->course->coach->name,
                    'day'               => $schedule->day
                ]);

                $scheduleJsons->push($scheduleJson);
            }

            $studentJson = collect([
                'student_id'        => $student->id,
                'name'              => $student->name,
                'parent_name'       => $student->parent_name,
                'phone'             => $student->phone,
                'email'             => $student->email,
                'photo_path'        => asset('storage/students/'. $student->image_profile),
                'schedules'         => $scheduleJsons
            ]);

            return new JsonResponse($studentJson);
        }
        catch (\Exception $ex){
            Log::error('Admin/CustomerController - getStudentScanResult - error EX: '. $ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
