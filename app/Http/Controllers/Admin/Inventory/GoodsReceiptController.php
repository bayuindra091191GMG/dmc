<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ItemReceiptHeader;
use App\Transformer\Inventory\ItemReceiptTransformer;
use Illuminate\Http\Request;

class GoodsReceiptController extends Controller
{
    //
    public function index(){
        return View('admin.inventory.goods_receipts.index');
    }

    public function create(){

    }

    public function store(){

    }

    public function edit(){

    }

    public function update(){

    }

    public function delete(){

    }

    public function print(){

    }

    public function getIndex(){
        $purchaseRequests = ItemReceiptHeader::all();
        return DataTables::of($purchaseRequests)
            ->setTransformer(new ItemReceiptTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
