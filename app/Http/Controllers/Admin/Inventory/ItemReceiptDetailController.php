<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ItemReceiptHeader;
use App\Transformer\Inventory\ItemReceiptTransformer;
use Illuminate\Http\Request;

class ItemReceiptDetailController extends Controller
{
    //
    public function index(){
        return View('admin.inventory.item_receipts.index');
    }

    public function create(){

        return View('admin.inventory.item_receipts.create');
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
}
