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
    //define return variable with default value
    protected $data = ['status' => false, 'msg' => 'init', 'icon' => 2];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @target: display the desktop page along with some init setups
     */
    public function index() {
        //get staff id through the login parts
        $staff_id = Auth::guard('admin')->user()->staff_id;

        //get positionId
        $positionId = Staff::where('staff_id', '=', $staff_id)->first()->position_id;

        //use the positionId to the designed menu for this logged in staff
        $menuList = $this->getMenuList($positionId);

        //get staff name through login parts
        $logedName = Auth::guard('admin')->user()->name;

        /*******get sales figures******************/
        //create sales object
        $salesObj = new SalesController();

        //get monthly sales for the sales report chart
        $monthlySales = $salesObj->monthlySales();

        //get the birthday list from database
        $birthday = $this->birthDayReminder();

        //get the logged in staff's todolist
        $todoLists = $this->getToDoList($staff_id);


        //if the view file is not exist, then copy the dashboard.balde.php file and rename it the needed file
        if(!view()->exists("admin\home\\$positionId")){ //the view template is not exist
            copy(resource_path("views\admin\home\dashboard.blade.php"),resource_path("views\admin\home\\{$positionId}.blade.php"));
        }


        //display the page with all variables above with it
        return view("admin/home/$positionId", ['menuList' => $menuList, 'name' => $logedName, 'monthlySales' => $monthlySales, 'birthday' => $birthday, 'todoLists' => $todoLists]);
    }

    /**
     * @param $positionId
     * @return mixed
     * @target get menu list for that position id with indexs
     */
    public function getMenuList($positionId) {

        //first get the menu(main menu) from the database
        $menuList = Menu::where('menu_position', '=', $positionId)->orderBy('rank')->get();

        //restructure the data for better display purpose
        foreach ($menuList as $key => $menu) {
            //get the submenu from the database then assign to the menuList array
            $menuList[$key][$menu->menu_name] = SubMenu::where('menu_id', '=', $menu->id)->orderBy('rank')->get();
        }
        return $menuList;
    }

    /**
     * @return mixed
     * @target get staffs who has birthday at current month
     */
    public function birthDayReminder() {
        //format the current month in to two digits number with dash at each end
        $currentMonth = sprintf("-%02d-", date("m"));

        //return the list of staff obj which they have birthday at current month
        return Staff::where('staff_dob', 'like', '%' . $currentMonth . '%')->get();
    }

    /**
     * @param Request $request
     * @return array
     * @target: save staff's todoList into database
     */
    public function addToDo(Request $request) {
        //$request contains: event and date

        //assign posted data into a variable for futher process
        $postData = $request->post();

        //assign staff_id to the variable gets from the login parts
        $postData['staff_id'] = Auth::guard('admin')->user()->staff_id;

        try { //fail safe
            //insert into database
            Todo::create($postData);
            //rewrite the return data
            $this->data = ['status' => true, 'msg' => '添加成功!', 'icon' => 1];

        } catch (\Exception $exception) {
            //rewrite the return data
            $this->data['msg'] = '添加失败!';
        }

        return $this->data;
    }

    /**
     * @param $staffId
     * @return mixed
     * @target get the todoList for the specified staff by staff id
     */
    private function getToDoList($staffId) {
        //get todoList for that particular staff identified by staff_id
        return Todo::where('staff_id', '=', $staffId)->get();
    }

    /**
     * @param Request $request
     * @return array
     * @target delete the todoEvent
     */
    public function delToDo(Request $request) {
        //$request: todoId

        //delete the todoEvent by todoId
        if (Todo::destroy($request->post('id'))) { //delete succeed
            //rewrite the return variable
            $this->data = ['status' => true, 'msg' => '删除成功', 'icon' => 2];
        } else { //delete failed
            //rewrite the return variable
            $this->data['msg'] = '删除错误';
        }

        return $this->data;
    }
}
