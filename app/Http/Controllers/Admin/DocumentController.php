<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Document;
use App\Models\NumberingSystem;
use App\Transformer\MasterData\DocumentTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.documents.index');
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
        $documents= Document::all();
        return DataTables::of($documents)
            ->setTransformer(new DocumentTransformer())
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
        return view('admin.documents.create');
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
            'description'   => 'required|max:45',
            'code'          => 'required|max:30|regex:/^\S*$/u|unique:documents'
        ],[
            'code.unique'   => 'Kode dokumen telah terpakai',
            'code.regex'    => 'Kode dokumen harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $doc = Document::create([
            'code'                 => $request->input('code'),
            'description'          => $request->input('description')
        ]);

        //Add Numbering System
        NumberingSystem::create([
            'doc_id'                => $doc->id,
            'next_no'               => 1
        ]);

        Session::flash('message', 'Berhasil membuat data dokumen baru!');

        return redirect()->route('admin.documents');
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
     * @param Document $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        return view('admin.documents.edit', ['document' => $document]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Document $document
     * @return mixed
     */
    public function update(Request $request, Document $document)
    {
        $validator = Validator::make($request->all(), [
            'description'   => 'required|max:45',
            'code'          => [
                'required',
                'max:45',
                'regex:/^\S*$/u',
                Rule::unique('documents')->ignore($document->id)
            ],
        ],[
            'code.unique'   => 'Kode dokumen telah terpakai',
            'code.regex'    => 'Kode dokumen harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $document->description = $request->input('description');
        $document->code = $request->input('code');
        $document->save();

//        return redirect()->intended(route('admin.documents'));
        Session::flash('message', 'Berhasil mengubah data dokumen!');

        return redirect()->route('admin.documents.edit', ['document' => $document]);
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
            $document = Document::find($request->input('id'));
            $document->delete();

            Session::flash('message', 'Berhasil menghapus data dokumen '. $document->description);

            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
