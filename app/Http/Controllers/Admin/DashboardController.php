<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApprovalPurchaseOrder;
use App\Models\ApprovalPurchaseRequest;
use App\Models\ApprovalRule;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\PreferenceCompany;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestHeader;
use Arcanedev\LogViewer\Entities\Log;
use Arcanedev\LogViewer\Entities\LogEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counts = [
            'users' => \DB::table('users')->count(),
            'users_unconfirmed' => \DB::table('users')->where('confirmed', false)->count(),
            'users_inactive' => \DB::table('users')->where('active', false)->count(),
            'protected_pages' => 0,
        ];

//        foreach (\Route::getRoutes() as $route) {
//            foreach ($route->middleware() as $middleware) {
//                if (preg_match("/protection/", $middleware, $matches)) $counts['protected_pages']++;
//            }
//        }

        // Get PR priority limit date warnings
        $purchaseRequests = new Collection();
        $prHeaders = PurchaseRequestHeader::where('status_id', 3)->get();
        foreach ($prHeaders as $header){
            if($header->priority_expired){
                $purchaseRequests->add($header);
            }
            else{
                if($header->day_left <= 3){
                    $purchaseRequests->add($header);
                }
            }
        }

        $user = Auth::user();

        // Check Approval Feature
        $preference = PreferenceCompany::find(1);
        $approvalPurchaseRequests = new Collection();
        $approvalPurchaseOrders = new Collection();

        if($preference->approval_setting === 1){
            // Get PR approval notifications
            if(ApprovalRule::where('document_id', 3)->where('user_id', $user->id)->exists()){
                foreach ($prHeaders as $header){
                    if(!ApprovalPurchaseRequest::where('purchase_request_id', $header->id)->where('user_id', $user->id)->exists()){
                        $approvalPurchaseRequests->add($header);
                    }
                }
            }

            // Get PO approval notifications
            $poHeaders = PurchaseOrderHeader::where('status_id', 3)->get();
            if(ApprovalRule::where('document_id', 4)->where('user_id', $user->id)->exists()){
                foreach ($poHeaders as $header){
                    if(!ApprovalPurchaseOrder::where('purchase_order_id', $header->id)->where('user_id', $user->id)->exists()){
                        $approvalPurchaseOrders->add($header);
                    }
                }
            }
        }



        $data = [
            'counts'                    => $counts,
            'prWarning'                 => $purchaseRequests,
            'approvalFeatured'          => $preference->approval_setting,
            'approvalPurchaseRequests'  => $approvalPurchaseRequests,
            'approvalPurchaseOrders'    => $approvalPurchaseOrders
        ];

        return view('admin.dashboard')->with($data);
    }


    public function getLogChartData(Request $request)
    {
        \Validator::make($request->all(), [
            'start' => 'required|date|before_or_equal:now',
            'end' => 'required|date|after_or_equal:start',
        ])->validate();

        $start = new Carbon($request->get('start'));
        $end = new Carbon($request->get('end'));

        $dates = collect(\LogViewer::dates())->filter(function ($value, $key) use ($start, $end) {
            $value = new Carbon($value);
            return $value->timestamp >= $start->timestamp && $value->timestamp <= $end->timestamp;
        });


        $levels = \LogViewer::levels();

        $data = [];

        while ($start->diffInDays($end, false) >= 0) {

            foreach ($levels as $level) {
                $data[$level][$start->format('Y-m-d')] = 0;
            }

            if ($dates->contains($start->format('Y-m-d'))) {
                /** @var  $log Log */
                $logs = \LogViewer::get($start->format('Y-m-d'));

                /** @var  $log LogEntry */
                foreach ($logs->entries() as $log) {
                    $data[$log->level][$log->datetime->format($start->format('Y-m-d'))] += 1;
                }
            }

            $start->addDay();
        }

        return response($data);
    }

    public function getRegistrationChartData()
    {

        $data = [
            'registration_form' => User::whereDoesntHave('providers')->count(),
            'google' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'google');
            })->count(),
            'facebook' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'facebook');
            })->count(),
            'twitter' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'twitter');
            })->count(),
        ];

        return response($data);
    }
}
