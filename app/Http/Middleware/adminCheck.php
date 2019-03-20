<?php

namespace App\Http\Middleware;

use App\Model\Controller;
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
        $data = ['status' => false,'msg' => "你没有授权, 无权操作!",'icon'=>2];


        if (Auth::guard('admin')->check()) {

            $positionId = Staff::find(Auth::guard('admin')->user()->staff_id)->position_id;

            if(!$this->accessCheck($positionId)){
                echo json_encode($data);
                exit;
            }



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

    private function accessCheck($positionId){
//            dump(Route::current()->getActionMethod());
//            dump(Route::current()->getAction());
//            dump(Route::current()->getAction()['controller']);
//            dump(Route::current()->getAction()['namespace']);


            $controller = str_replace(Route::current()->getAction()['namespace']."\\",'',Route::current()->getAction()['controller']);
            $controller = str_replace("@".Route::current()->getActionMethod(),'',$controller);
            $action = Route::current()->getActionMethod();


            return Controller::where('position_id','=',$positionId)->where('controller','=',$controller)
                ->leftjoin('functions','controllers.id','=','functions.controller_id')
                ->where('functions.function','=',$action)
                ->exists();
    }



}
