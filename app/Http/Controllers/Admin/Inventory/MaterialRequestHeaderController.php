<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 3/20/2018
 * Time: 11:22 AM
 */

namespace App\Http\Controllers\Admin\Inventory;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\MaterialRequestDetail;
use App\Models\MaterialRequestHeader;
use App\Models\NumberingSystem;
use App\Notifications\MaterialRequestCreated;
use App\Transformer\Inventory\MaterialRequestHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MaterialRequestHeaderController extends Controller
{
    public function indexOther(){
        return View('admin.inventory.material_requests.other.index');
    }

    public function indexFuel(){
        return View('admin.inventory.material_requests.fuel.index');
    }

    public function indexOil(){
        return View('admin.inventory.material_requests.oil.index');
    }

    public function indexService(){
        return View('admin.inventory.material_requests.service.index');
    }

    public function createOther(){
        $departments = Department::all();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '9')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'departments'   => $departments,
            'autoNumber'    => $autoNumber
        ];

        return View('admin.inventory.material_requests.other.create')->with($data);
    }

    public function createFuel(){
        $departments = Department::all();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '10')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'departments'   => $departments,
            'autoNumber'    => $autoNumber
        ];

        return View('admin.inventory.material_requests.fuel.create')->with($data);
    }

    public function createOil(){
        $departments = Department::all();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '11')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'departments'   => $departments,
            'autoNumber'    => $autoNumber
        ];

        return View('admin.inventory.material_requests.oil.create')->with($data);
    }

    public function createService(){
        $departments = Department::all();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '12')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'departments'   => $departments,
            'autoNumber'    => $autoNumber
        ];

        return View('admin.inventory.material_requests.service.create')->with($data);
    }

    public function showOther(MaterialRequestHeader $material_request){
        $header = $material_request;
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $itemStocks = new Collection();

        if($header->status_id == 3){
            // Check stock
            $isInStock = true;
            foreach($header->material_request_details as $detail){
                if($detail->item->stock < $detail->quantity){
                    $isInStock = false;
                }
            }

            // Get stock
            if($isInStock){
                foreach($header->material_request_details as $detail){
                    $stocks = ItemStock::where('item_id', $detail->item_id)->get();
                    foreach($stocks as $stock){
                        $itemStocks->add($stock);
                    }
                }
            }
        }

        $data = [
            'header'        => $header,
            'date'          => $date,
            'itemStocks'    => $itemStocks
        ];

        return View('admin.inventory.material_requests.other.show')->with($data);
    }

    public function showFuel(MaterialRequestHeader $material_request){
        $header = $material_request;
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'date'          => $date
        ];

        return View('admin.inventory.material_requests.fuel.show')->with($data);
    }

    public function showOil(MaterialRequestHeader $material_request){
        $header = $material_request;
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'date'          => $date
        ];

        return View('admin.inventory.material_requests.oil.show')->with($data);
    }

    public function showService(MaterialRequestHeader $material_request){
        $header = $material_request;
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'date'          => $date
        ];

        return View('admin.inventory.material_requests.service.show')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'mr_code'       => 'required|max:30|regex:/^\S*$/u',
            'km'            => 'max:20',
            'hm'            => 'max:20',
            'date'          => 'required'
        ],[
            'mr_code.required'      => 'Nomor MR wajib diisi!',
            'mr_code.regex'         => 'Nomor MR harus tanpa spasi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate department
        if($request->input('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        // Validate priority
        if($request->input('priority') === '-1'){
            return redirect()->back()->withErrors('Pilih prioritas!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $type = $request->input('type');
        $docId = 0;
        if($type == '1'){
            $docId = 9;
        }
        else if($type == '2'){
            $docId = 10;
        }
        else if($type == '3'){
            $docId = 11;
        }
        else{
            $docId = 12;
        }

        $mrCode = 'default';
        if($request->filled('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', $docId)->first();
            $mrCode = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $mrCode = $request->input('mr_code');
        }

        // Check existing number
        if(MaterialRequestHeader::where('code', $mrCode)->exists()){
            return redirect()->back()->withErrors('Nomor MR sudah terdaftar!', 'default')->withInput($request->all());
        }

        // Validate details
        $items = $request->input('item');

        if(count($items) == 0){
            return redirect()->back()->withErrors('Detail barang wajib diisi!', 'default')->withInput($request->all());
        }

        $qtys = $request->input('qty');
        $valid = true;
        $i = 0;
        foreach($items as $item){
            if(empty($item)) $valid = false;
            if(empty($qtys[$i]) || $qtys[$i] == '0') $valid = false;
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail barang dan jumlah wajib diisi!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $mrHeader = MaterialRequestHeader::create([
            'code'              => $mrCode,
            'type'              => $type,
            'department_id'     => $request->input('department'),
            'priority'          => $request->input('priority'),
            'km'                => $request->input('km'),
            'hm'                => $request->input('hm'),
            'status_id'         => 3,
            'created_by'        => $user->id,
            'created_at'        => $now->toDateTimeString(),
            'updated_by'        => $user->id

        ]);

        if($request->filled('machinery')){
            $mrHeader->machinery_id = $request->input('machinery');
            $mrHeader->save();
        }

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $mrHeader->date = $date->toDateTimeString();
        $mrHeader->save();

        // Create material request detail
        $qty = $request->input('qty');
        $remark = $request->input('remark');
        $idx = 0;
        foreach($request->input('item') as $item){
            if(!empty($item)){
                $prDetail = MaterialRequestDetail::create([
                    'header_id'         => $mrHeader->id,
                    'item_id'           => $item,
                    'quantity'          => $qty[$idx],
                    'quantity_received' => 0,
                    'quantity_issued'   => 0
                ]);

                if(!empty($remark[$idx])) $prDetail->remark = $remark[$idx];
                $prDetail->save();
            }
            $idx++;
        }

        // Check stock
        $isInStock = true;
        $idx = 0;
        foreach($request->input('item') as $item){
            if(!empty($item)){
                $qtyInt = (int) $qty[$idx];
                $item = Item::find($item);
                if($item->stock < $qtyInt){
                    $isInStock = false;
                }
            }
            $idx++;
        }

        // Notification
        if(!$isInStock){
            $roleIds = [4,5];
        }
        else{
            $roleIds = [4,6];
        }
        $roles = Role::whereIn('id', $roleIds)->get();
        foreach($roles as $role){
            $users =  $role->users()->get();
            if($users->count() > 0){
                foreach ($users as $notifiedUser){
                    $notifiedUser->notify(new MaterialRequestCreated($mrHeader, $isInStock));
                }
            }
        }


        Session::flash('message', 'Berhasil membuat material request!');

        if($type === '1'){
            return redirect()->route('admin.material_requests.other.show', ['material_request' => $mrHeader]);
        }
        else if($type === '2'){
            return redirect()->route('admin.material_requests.fuel.show', ['material_request' => $mrHeader]);
        }
        else{
            return redirect()->route('admin.material_requests.service.show', ['material_request' => $mrHeader]);
        }
    }

    public function editOther(MaterialRequestHeader $material_request){
        $header = $material_request;
        $departments = Department::all();
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'departments'   => $departments,
            'date'          => $date
        ];

        return View('admin.inventory.material_requests.other.edit')->with($data);
    }

    public function editFuel(MaterialRequestHeader $material_request){
        $header = $material_request;
        $departments = Department::all();
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'departments'   => $departments,
            'date'          => $date
        ];

        return View('admin.inventory.material_requests.fuel.edit')->with($data);
    }

    public function editOil(MaterialRequestHeader $material_request){
        $header = $material_request;
        $departments = Department::all();
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'departments'   => $departments,
            'date'          => $date
        ];

        return View('admin.inventory.material_requests.oil.edit')->with($data);
    }

    public function editService(MaterialRequestHeader $material_request){
        $header = $material_request;
        $departments = Department::all();
        $date = Carbon::parse($material_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'departments'   => $departments,
            'date'          => $date
        ];

        return View('admin.inventory.material_requests.service.edit')->with($data);
    }

    public function update(Request $request, MaterialRequestHeader $material_request){
        $validator = Validator::make($request->all(),[
            'km'            => 'max:20',
            'hm'            => 'max:20',
            'date'          => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $type = $request->input('type');

        // Validate department
        if($request->input('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        // Validate priority
        if($request->input('priority') === '-1'){
            return redirect()->back()->withErrors('Pilih prioritas!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $material_request->department_id = $request->input('department');
        $material_request->priority = $request->input('priority');
        $material_request->km = $request->input('km');
        $material_request->hm = $request->input('hm');
        $material_request->date = $date;
        $material_request->updated_by = $user->id;
        $material_request->updated_at = $now->toDateTimeString();

        if($request->filled('machinery')){
            $material_request->machinery_id = $request->input('machinery');
        }

        $material_request->save();

        Session::flash('message', 'Berhasil ubah material request!');

        if($type === '1'){
            return redirect()->route('admin.material_requests.other.show', ['material_request' => $material_request]);
        }
        else if($type === '2'){
            return redirect()->route('admin.material_requests.fuel.show', ['material_request' => $material_request]);
        }
        else if($type === '3'){
            return redirect()->route('admin.material_requests.oil.show', ['material_request' => $material_request]);
        }
        else{
            return redirect()->route('admin.material_requests.service.show', ['material_request' => $material_request]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getIndex(Request $request){

        $type = 'default';
        if($request->filled('type')){
            $type = $request->input('type');
        }

        $materialRequests = null;
        if($type === 'part'){
            $materialRequests = MaterialRequestHeader::where('type', 1)->get();
        }
        else if($type === 'fuel'){
            $materialRequests = MaterialRequestHeader::where('type', 2)->get();
        }
        else if($type === 'oil'){
            $materialRequests = MaterialRequestHeader::where('type', 3)->get();
        }
        else if($type === 'service'){
            $materialRequests = MaterialRequestHeader::where('type', 4)->get();
        }
        else if($type === 'before_create' || $type === 'before_create_id'){
            $materialRequests = MaterialRequestHeader::where('status_id', 3)->get();
        }
        else{
            $materialRequests = MaterialRequestHeader::all();
        }

        return DataTables::of($materialRequests)
            ->setTransformer(new MaterialRequestHeaderTransformer($type))
            ->addIndexColumn()
            ->make(true);
    }

    public function getMaterialRequests(Request $request){
        $term = trim($request->q);
        $materialRequests = MaterialRequestHeader::where('status_id', 3)
            ->where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($materialRequests as $materialRequest) {
            $formatted_tags[] = ['id' => $materialRequest->id, 'text' => $materialRequest->code];
        }

        return \Response::json($formatted_tags);
    }

    public function close(Request $request){
        try{
            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $materialRequest = MaterialRequestHeader::find($request->input('id'));
            $materialRequest->closed_by = $user->id;
            $materialRequest->closed_at = $now->toDateTimeString();
            $materialRequest->close_reason = $request->input('reason');
            $materialRequest->status_id = 11;
            $materialRequest->save();

            Session::flash('message', 'Berhasil tutup MR!');

            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function printDocument($id){
        $materialRequest = MaterialRequestHeader::find($id);

        return view('documents.material_requests.material_requests_doc', compact('materialRequest'));
    }
}