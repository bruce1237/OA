<?php

namespace App\Http\Controllers\Admin;

use App\Model\Menu;
use App\Model\Staff;
use App\Model\SubMenu;
use App\Model\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $data = ['status'=>false,'msg'=>'init'];

    public function __construct() {

    }

    public function index(){
        $staff_id = Auth::guard('admin')->user()->staff_id;
        $menuList = $this->getMenuList($staff_id);
        //get positionId
        $positionId = Staff::where('staff_id','=',$staff_id)->first()->position_id;
        $logedName = Auth::guard('admin')->user()->name;
        $salesObj = new SalesController();
        $monthlySales = $salesObj->monthlySales();
        $birthday = $this->birthDayReminder();

        $todoLists = $this->getToDoList($staff_id);







        return view("admin/home/$positionId",['menuList'=>$menuList,'name'=>$logedName, 'monthlySales'=>$monthlySales, 'birthday'=>$birthday,'todoLists' =>$todoLists]);
    }

    public function getMenuList($staffId){
        $positionId = Staff::find($staffId)->position_id;
        $menuList = Menu::where('menu_position','=',$positionId)->orderBy('rank')->get();
        foreach ($menuList as $key=> $menu){
            $menuList[$key][$menu->menu_name] = SubMenu::where('menu_id','=',$menu->id)->orderBy('rank')->get();
        }
        return $menuList;
    }

    public function birthDayReminder(){
        $currentMonth = sprintf("-%02d-", date("m"));
        $currentMonth = sprintf("-%02d-", 1);
        return Staff::where('staff_dob','like','%'.$currentMonth.'%')->get();
    }

    public function addToDo(Request $request){
       $postData = $request->post();
       $postData['staff_id'] = Auth::guard('admin')->user()->staff_id;

       try{
           Todo::create($postData);
           $this->data['status'] = true;
           $this->data['msg'] = '添加成功!';

       }catch (\Exception $exception){
           $this->data['msg'] = '添加失败!';
       }

       return $this->data;
    }

    private function getToDoList($staffId){
        return Todo::where('staff_id','=',$staffId)->get();
    }

    public function delToDo(Request $request){
        if(Todo::destroy($request->post('id'))){
            $this->data['status'] = true;
        }else{
            $this->data['msg'] = '删除错误';
        }
        return $this->data;
    }





}
