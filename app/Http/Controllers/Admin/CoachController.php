<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Transformer\MasterData\CoachTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.coaches.index');
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
        $coaches = Coach::where('id', '!=', 0)->get();
        return DataTables::of($coaches)
            ->setTransformer(new CoachTransformer())
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
        return view('admin.coaches.create');
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
            'email'             => 'email'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        Coach::create([
            'name'          => $request->get('name'),
            'phone'         => $request->get('phone'),
            'email'         => $request->get('email'),
            'address'       => $request->get('address'),
            'status_id'     => 1
        ]);

        Session::flash('message', 'Berhasil membuat data Trainer baru!');

        return redirect()->route('admin.coaches');
    }

    /**
     * Display the specified resource.
     *
     * @param Coach $coach
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Coach $coach)
    {
        $courses = Course::where('coach_id', $coach->id)->get();
        return view('admin.coaches.show', ['coach' => $coach, 'courses' => $courses]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Coach $coach
     * @return \Illuminate\Http\Response
     */
    public function edit(Coach $coach)
    {
        return view('admin.coaches.edit', ['coach' => $coach]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Coach $coach
     * @return mixed
     */
    public function update(Request $request, Coach $coach)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'email'             => 'email'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $coach->name = $request->get('name');
        $coach->email = $request->get('email');
        $coach->address = $request->get('address');
        $coach->phone = $request->get('phone');
        $coach->updated_at = $dateTimeNow;
        $coach->save();

        Session::flash('message', 'Berhasil mengubah data Trainer!');

        return redirect()->route('admin.coaches.edit', ['coach' => $coach]);
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
            $coach = Coach::find($request->input('id'));

            //Check first if Trainer already in Transaction
            $classes = Course::where('coach_id', $coach->id)->get();
            if($classes != null){
                foreach ($classes as $data){
                    $transaction = TransactionDetail::where('class_id', $data->id)->get();
                    if($transaction != null){
                        Session::flash('error', 'Data Trainer '. $coach->name . ' Tidak dapat dihapus karena masih wajib mengajar!');
                        return Response::json(array('success' => 'VALID'));
                    }
                }
            }
            $coach->delete();

            Session::flash('message', 'Berhasil menghapus data Trainer '. $coach->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
