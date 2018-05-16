<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\TransactionHeader;
use App\Transformer\MasterData\CourseTransformer;
use App\Transformer\MasterData\CustomerTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.courses.index');
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
        $courses = Course::all();
        return DataTables::of($courses)
            ->setTransformer(new CourseTransformer())
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
        $coaches = Coach::all();
        return view('admin.courses.create', compact('coaches'));
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
            'name'              => 'required|max:50',
            'type'              => 'required',
            'price'             => 'required',
            'meeting_amount'    => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        Customer::create([
            'name'              => $request->get('name'),
            'type'              => $request->get('type'),
            'price'             => $request->get('price'),
            'coach_id'          => $request->get('coach'),
            'meeting_amount'    => $request->get('meeting_amount'),
        ]);

        Session::flash('message', 'Berhasil membuat data Kelas baru!');

        return redirect()->route('admin.courses');
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
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', ['course' => $course]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Course $course
     * @return mixed
     */
    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'phone'             => 'required',
            'age'               => 'required',
            'email'             => 'required|email',
            'address'           => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $course->name = $request->get('name');
        $course->type = $request->get('type');
        $course->price = $request->get('price');
        $course->coach_id = $request->get('coach');
        $course->meeting_amount = $request->get('meeting_amount');
        $course->updated_at = $dateTimeNow;
        $course->save();

        Session::flash('message', 'Berhasil mengubah data Kelas!');

        return redirect()->route('admin.courses.edit', ['course' => $course]);
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
            $customer = Menu::find($request->input('id'));

            //Check first if User already in Transaction
            $transaction = TransactionHeader::where('customer_id', $request->input('id'))->get();
            if($transaction != null){
                Session::flash('error', 'Data Customer '. $customer->name . ' Tidak dapat dihapus karena masih memiliki Kelas atau Paket!');
                return Response::json(array('success' => 'VALID'));
            }
            $customer->delete();

            Session::flash('message', 'Berhasil menghapus data Customer '. $customer->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
