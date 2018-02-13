<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
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
            'name'      => 'required',
            'code'      => 'required',
            'location'  => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        Site::create([
            'name'      => $request->get('name'),
            'code'      => $request->get('code'),
            'location'  => $request->get('location')
        ]);

        Session::flash('message', 'Berhasil membuat Site Baru!');

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $site = Site::find($id);

        return view('admin.sites.edit', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'code'      => 'required',
            'location'  => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $site = Site::find($id);
        $site->name = $request->get('name');
        $site->code = $request->get('code');
        $site->location = $request->get('location');

        $site->save();

        Session::flash('message', 'Sukses mengubah data!');

        return redirect(route('admin.sites.edit', ['site' => $site->id]));
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
        $sites = Site::all();
        return DataTables::of($sites)
            ->addIndexColumn()
            ->addColumn('action', function($site){
                return '<a class="btn btn-xs btn-info" href="sites/'.$site->id.'/ubah" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>';
            })
            ->make(true);
    }
}
