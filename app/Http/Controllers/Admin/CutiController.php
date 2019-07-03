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
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CutiController extends Controller
{
    public function index()
    {
        $type = 0;
        return view('admin.cuti.index', compact('type'));
    }

    public function indexMuaythai()
    {
        $type = 1;
        return view('admin.cuti.index', compact('type'));
    }

    public function indexDance()
    {
        $type = 2;
        return view('admin.cuti.index', compact('type'));
    }

    public function indexPrivate()
    {
        $type = 3;
        return view('admin.cuti.index', compact('type'));
    }

    public function indexGymnastic()
    {
        $type = 4;
        return view('admin.cuti.index', compact('type'));
    }

    public function getIndex(Request $request)
    {
        $type = $request->input('type');

        $leaves = Leaf::whereHas('schedule', function($query1) use ($type){
                $query1->whereHas('course', function($query2) use ($type){
                    $query2->where('type', $type);
                });
            })
            ->get();

        return DataTables::of($leaves)
            ->setTransformer(new CutiTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}