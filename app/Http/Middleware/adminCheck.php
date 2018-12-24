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
            $positionAllowedAccessArray = json_decode(file_get_contents(storage_path('access' . "/" . $positionId)), true);
//            dump($positionAllowedAccessArray);
//dump(Route::current()->uri());
            $this->convertArray($positionAllowedAccessArray);
//            dump($this->arrString);
//            dump(Route::currentRouteAction());

//            $auth=false;

            //first, check if the page is allowed to be shown
            if (strtolower(Route::current()->uri()) != "admin/home") {

                if (!key_exists(strtolower(Route::current()->uri()), $positionAllowedAccessArray)) {
//                    return redirect('admin/denied');
//                echo json_encode($data);
//                exit;
//                    $auth=false;

                }
            }

            //next, check if the page

            $currentFunctionName = (substr(Route::currentRouteAction(), strpos(Route::currentRouteAction(), '@') + 1));

//            dump(strtolower($currentFunctionName));
//
//            dump($positionAllowedAccessArray['admin/hr']);
////
//
//

//            $data=array();
            $this->convertArray($positionAllowedAccessArray);
//            dump($this->arrString);
//            dump(strtolower($currentFunctionName));
//////
//            dump(in_array(strtolower($currentFunctionName),$this->arrString));
////
            if (!in_array(strtolower($currentFunctionName), $this->arrString)) {
//                            dump(strtolower($currentFunctionName));
//                return redirect('admin/denied');
                $auth=false;



//               session()->put('authorize','F');

//                dd(session()->all());

//                dump("JJ");
//                return redirect('admin/OAMenu');

//                echo json_encode($data);
//              exit;

            }else{
//                session()->put('authorize','T');
                $auth = true;
            }


            file_put_contents(storage_path('access')."/access.txt",json_encode($auth));
//
//            session(['authorize'=>'F']);
//            echo session('authorize');




//            dump(Route::current()->uri());
//
//
//            dump(Route::current()->uri());
//            dump(Route::current()->action['namespace']);
//
//            dump(Route::currentRouteAction());

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
