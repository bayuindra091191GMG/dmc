<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 02/05/2018
 * Time: 9:13
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function redirect(){
        $url = "default";
        if(!empty(request()->url)){
            $url = request()->url;
        }

        if(!Auth::user()){
            return redirect()->route('login', ['redirect' => $url]);
        }
        else{
            if($url === "default"){
                return redirect()->route('dashboard');
            }
            else{
                return redirect($url);
            }
        }
    }
}