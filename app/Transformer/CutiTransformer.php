<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 17/10/2018
 * Time: 10:24
 */

namespace App\Transformer;


use App\Models\Leaf;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CutiTransformer extends TransformerAbstract
{
    public function transform(Leaf $cuti){
        $dateStart = Carbon::parse($cuti->start_date)->toIso8601String();
        $dateEnd = Carbon::parse($cuti->end_date)->toIso8601String();

        $trxShowUrl = route('admin.transactions.show', ['transaction' => $cuti->transaction_id]);
        $trxHref = "<a name='". $cuti->transaction_header->code. "' style='text-decoration: underline;' href='". $trxShowUrl. "' target='_blank'>". $cuti->transaction_header->code. "</a>";

        return[
            'start_date'        => $dateStart,
            'end_date'          => $dateEnd,
            'transaction'       => $trxHref,
            'student_name'      => $cuti->schedule->customer->name,
            'student_parent'    => $cuti->schedule->customer->parent_name ?? "-",
            'class'             => $cuti->schedule->course->name,
            'coach'             => $cuti->schedule->course->coach->name
        ];
    }
}