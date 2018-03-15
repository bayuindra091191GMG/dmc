<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Warehouse;
use App\Transformer\MasterData\SiteTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Validator;

class SiteController extends Controller
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
        return view('admin.sites.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'              => 'required|max:30|regex:/^\S*$/u|unique:sites',
            'name'              => 'required|max:45',
            'location'          => 'max:30',
            'phone'             => 'max:20',
            'pic'               => 'max:45'
        ],[
            'code.unique'           => 'Kode site telah terpakai!',
            'code.regex'            => 'Kode site harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        Site::create([
            'name'          => $request->input('name'),
            'code'          => $request->input('code'),
            'location'      => $request->input('location'),
            'phone'         => $request->input('phone'),
            'pic'           => $request->input('pic'),
            'created_by'    => $user->id,
            'created_at'    => $now->toDateTimeString(),
            'updated_by'    => $user->id,
        ]);

        Session::flash('message', 'Berhasil membuat site baru!');

        return redirect(route('admin.sites'));
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
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        return view('admin.sites.edit', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Site $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        //
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'max:30',
                'regex:/^\S*$/u',
                Rule::unique('sites')->ignore($site->id)
            ],
            'name'              => 'required|max:45',
            'location'          => 'max:30',
            'phone'             => 'max:20',
            'pic'               => 'max:45'
        ],[
            'code.unique'           => 'Kode site telah terpakai!',
            'code.regex'            => 'Kode site harus tanpa spasi',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        // Update site
        $site->name = $request->input('name');
        $site->code = $request->input('code');
        $site->location = $request->input('location');
        $site->phone = $request->input('phone');
        $site->pic = $request->input('pic');

        $site->save();

        Session::flash('message', 'Sukses mengubah data site!');

        return redirect(route('admin.sites.edit', ['site' => $site->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try{
            $site = Site::find($request->input('id'));
            $site->delete();

            Session::flash('message', 'Berhasil menghapus data site '. $site->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getIndex()
    {
        $sites = Site::all();
        return DataTables::of($sites)
            ->setTransformer(new SiteTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}
