<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Machinery;
use App\Models\MachineryBrand;
use App\Models\MachineryCategory;
use App\Models\MachineryType;
use App\Transformer\MasterData\MachineryTransformer;
use App\Transformer\MasterData\MachineryTypeTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MachineryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.machineries.index');
    }

    //DataTables

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndex()
    {
        $machineries = Machinery::all();
        return DataTables::of($machineries)
            ->setTransformer(new MachineryTransformer())
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
        $machineryCategories = MachineryCategory::all();
        $machineryBrands = MachineryBrand::all();

        $data = [
            'machineryCategories'   => $machineryCategories,
            'machineryBrands'       => $machineryBrands
        ];

        return view('admin.machineries.create')->with($data);
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
            'code' => 'required|max:45',
            'description' => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        if(Input::get('machinery_category') === '-1'){
            return redirect()->back()->withErrors('Pilih kategori alat berat!', 'default')->withInput($request->all());
        }

        if(Input::get('machinery_brand') === '-1'){
            return redirect()->back()->withErrors('Pilih merek alat berat!', 'default')->withInput($request->all());
        }

        if(empty(Input::get('machinery_type'))){
            return redirect()->back()->withErrors('Pilih tipe alat berat!', 'default')->withInput($request->all());
        }

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $machinery = Machinery::create([
            'code'                  => $request->get('code'),
            'category_id'           => Input::get('machinery_category'),
            'brand_id'              => Input::get('machinery_brand'),
            'type_id'               => Input::get('machinery_type'),
            'description'           => Input::get('description'),
            'status_id'             => 1,
            'created_by'            => 1,
            'created_at'            => $dateTimeNow->toDateTimeString()
        ]);

        Session::flash('message', 'Berhasil membuat data alat berat baru!');

        return redirect()->route('admin.machineries');
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
     * @param Machinery $machinery
     * @return \Illuminate\Http\Response
     */
    public function edit(Machinery $machinery)
    {
        $machineryCategories = MachineryCategory::all();
        $machineryBrands = MachineryBrand::all();

        $data = [
            'machinery'             => $machinery,
            'machineryCategories'   => $machineryCategories,
            'machineryBrands'       => $machineryBrands
        ];

        return view('admin.machineries.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Machinery $machinery
     * @return mixed
     */
    public function update(Request $request, Machinery $machinery)
    {
//        dd(Input::get('machinery_type'));

        $validator = Validator::make($request->all(), [
            'code' => 'required|max:45',
            'description' => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $user = Auth::user();
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $machinery->code = Input::get('code');

        if(!empty(Input::get('machinery_type'))){
            $machinery->type_id = Input::get('machinery_type');
        }

        $machinery->category_id = Input::get('machinery_category');
        $machinery->brand_id = Input::get('machinery_brand');
        $machinery->description = Input::get('description');
        $machinery->updated_at = $dateTimeNow->toDateTimeString();
        $machinery->updated_by = $user->id;

        $machinery->save();

        Session::flash('message', 'Berhasil mengubah data alat berat!');

        return redirect()->route('admin.machineries.edit', ['machinery' => $machinery->id]);
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
