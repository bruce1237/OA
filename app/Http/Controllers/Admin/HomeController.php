<?php

namespace App\Http\Controllers\Admin;

use App\Model\Menu;
use App\Model\Staff;
use App\Model\SubMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct() {

    }

    public function index(){
        $menuList = $this->getMenuList(Auth::guard('admin')->user()->staff_id);
        //get positionId
        $positionId = Staff::where('staff_id','=',Auth::guard('admin')->user()->staff_id)->first()->position_id;
        $logedName = Auth::guard('admin')->user()->name;
        $salesObj = new SalesController();
        $monthlySales = $salesObj->monthlySales();

        return view("admin/home/$positionId",['menuList'=>$menuList,'name'=>$logedName, 'monthlySales'=>$monthlySales]);
    }








    public function getMenuList($staffId){
        $positionId = Staff::find($staffId)->position_id;
        $menuList = Menu::where('menu_position','=',$positionId)->orderBy('rank')->get();
        foreach ($menuList as $key=> $menu){
            $menuList[$key][$menu->menu_name] = SubMenu::where('menu_id','=',$menu->id)->orderBy('rank')->get();
        }
        return $menuList;
    }





}
