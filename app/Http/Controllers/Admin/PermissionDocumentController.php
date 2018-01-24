<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Document;
use App\Models\PermissionDocument;
use App\Transformer\MasterData\PermissionDocumentTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Validator;


class PermissionDocumentController extends Controller
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
        return view('admin.permission_documents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        $documents = Document::all();

        return view('admin.permission_documents.create', compact('roles', 'documents'));
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
            'role' => 'required',
            'document' => 'required',
            'read' => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $role_id = $request->get('role');
        $document_id = $request->get('document');

        $permission = PermissionDocument::where('role_id', $role_id)->where('document_id', $document_id)->first();
        if($permission != null){
            return redirect()->back()->withErrors('Data Sudah dibuat!');
        }

        if($request->get('read')){
            $read = 1;
        }
        else{
            $read = 0;
        }
        if($request->get('create')){
            $create = 1;
        }
        else{
            $create = 0;
        }
        if($request->get('update')){
            $update = 1;
        }
        else{
            $update = 0;
        }
        if($request->get('delete')){
            $delete = 1;
        }
        else{
            $delete = 0;
        }
        if($request->get('print')){
            $print = 1;
        }
        else{
            $print = 0;
        }

        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        PermissionDocument::create([
            'role_id'       => $request->get('role'),
            'document_id'   => $request->get('document'),
            'read'          => $read,
            'create'        => $create,
            'update'        => $update,
            'delete'        => $delete,
            'print'         => $print,
            'created_at'    => $dateTimeNow->toDateTimeString(),
            'created_by'    => $user->id,
            'updated_at'    => $dateTimeNow->toDateTimeString(),
            'updated_by'    => $user->id
        ]);

        Session::flash('message', 'Sukses membuat Otorisasi Dokumen Baru!');

        return redirect(route('admin.permission_documents.create'));
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
        $permissionDocument = PermissionDocument::find($id);
        $roles = Role::all();
        $documents = Document::all();

        return view('admin.permission_documents.edit', compact('roles', 'documents', 'permissionDocument'));
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
            'role' => 'required',
            'document' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $permissionDocument = PermissionDocument::find($id);
        if($request->get('read')){
            $permissionDocument->read = 1;
        }
        else{
            $permissionDocument->read = 0;
        }
        if($request->get('create')){
            $permissionDocument->create = 1;
        }
        else{
            $permissionDocument->create = 0;
        }
        if($request->get('update')){
            $permissionDocument->update = 1;
        }
        else{
            $permissionDocument->update = 0;
        }
        if($request->get('delete')){
            $permissionDocument->delete = 1;
        }
        else{
            $permissionDocument->delete = 0;
        }
        if($request->get('print')){
            $permissionDocument->print = 1;
        }
        else{
            $permissionDocument->print = 0;
        }

        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        $permissionDocument->role_id = $request->get('role');
        $permissionDocument->document_id = $request->get('document');
        $permissionDocument->updated_at = $dateTimeNow->toDateTimeString();
        $permissionDocument->updated_by = $user->id;
        $permissionDocument->save();

        Session::flash('message', 'Sukses Mengubah Data!');

        return redirect(route('admin.permission_documents.edit', ['permission_document' => $permissionDocument->id]));
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

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        $permissionDocuments = PermissionDocument::all();
        return DataTables::of($permissionDocuments)
            ->setTransformer(new PermissionDocumentTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
