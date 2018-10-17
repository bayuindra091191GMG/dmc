<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 17/10/2018
 * Time: 10:23
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Leaf;
use App\Transformer\CutiTransformer;
use Yajra\DataTables\DataTables;

class CutiController extends Controller
{
    public function index()
    {
        return view('admin.cuti.index');
    }

    public function getIndex()
    {
        $leaves = Leaf::all();
        return DataTables::of($leaves)
            ->setTransformer(new CutiTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}