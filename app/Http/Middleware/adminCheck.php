<?php

namespace App\Http\Middleware;

use App\Model\Menu;
use App\Model\Staff;
use App\Model\SubMenu;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class adminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    protected $arrString = array();


    public function handle($request, Closure $next) {
        $data['status'] = false;
        $data['msg'] = "你没有授权, 无权操作!";

//        dump(Route::current()->uri());
        if (Auth::guard('admin')->check()) {

            $positionId = Staff::find(Auth::guard('admin')->user()->staff_id)->position_id;
            $positionAllowedAccessArray = file_exists(storage_path('access' . "/" . $positionId)) ? json_decode(file_get_contents(storage_path('access' . "/" . $positionId)), true) : [];
//            dump($positionAllowedAccessArray);
//            dump(Route::current()->uri());
            $this->convertArray($positionAllowedAccessArray);
//            dump($this->arrString);
//            dump(Route::currentRouteAction());

            $auth = false;

            //first, check if the page is allowed to be shown
            if (strtolower(Route::current()->uri()) != "admin/home") {
//                dump($positionAllowedAccessArray);
//                dump(Route::current()->action);
                if (!key_exists(strtolower(Route::current()->uri()), $positionAllowedAccessArray)) {
//                if (!key_exists(Route::current()->uri(), $positionAllowedAccessArray)) {
//dd("FF");
                    $auth = false;

                } else {
                    //next, check if the page
                    $currentFunctionName = (substr(Route::currentRouteAction(), strpos(Route::currentRouteAction(), '@') + 1));
                    if (in_array(strtolower($currentFunctionName), $this->arrString)) {
                        $auth = true;
                    }
                }
            }

//            if(!$auth){
//
//                echo json_encode($data);
//                exit;
//            }




            return $next($request);
        } else {
            return redirect('/login');
        }
    }


    public function convertArray($array) {
        foreach ($array as $value) {
            if (is_array($value)) {
                $this->convertArray($value);
            } else {
                array_push($this->arrString, $value);
            }
        }

    }
}
