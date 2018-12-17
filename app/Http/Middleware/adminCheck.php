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
    public function handle($request, Closure $next) {
        if (Auth::guard('admin')->check()) {

            $positionId = Staff::find(Auth::guard('admin')->user()->staff_id)->position_id;
            $positionAllowedAccessArray = json_decode(file_get_contents(storage_path('access' . "/" . $positionId)), true);
//            dump($positionAllowedAccessArray);


            $currentFunctionName = (substr(Route::currentRouteAction(), strpos(Route::currentRouteAction(), '@') + 1));

            dump(strtolower($currentFunctionName));

            dump($positionAllowedAccessArray);
//


            dump(in_array(strtolower($currentFunctionName),$positionAllowedAccessArray));


//            dump(Route::current()->uri());
//
//            dump($request->getMethod());
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


    public function getMenuList($staffId) {
        $positionId = Staff::find($staffId)->position_id;
        $aaa = array();
        $menuList = Menu::where('menu_position', '=', $positionId)->orderBy('rank')->get();

        foreach ($menuList as $key => $menu) {

            $aaa[] = SubMenu::where('menu_id', '=', $menu->id)->orderBy('rank')->select('id')->get();
        }

//        dd($aaa);
        return $aaa;
    }
}
