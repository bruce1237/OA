<?php

namespace App\Http\Controllers\Admin;

use App\Model\Menu;
use App\Model\Position;
use App\Model\SubMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    protected $positionObj;
    protected $menuObj;
    protected $submenuObj;
    protected $data = ['status' => false, 'msg' => 'init'];


    public function __construct() {
        $this->positionObj = new Position();
        $this->menuObj = new Menu();
        $this->submenuObj = new SubMenu();


    }

    //
    public function index() {
        //get position from database, used for group the menu and submenu

        $positions = $this->positionObj->all();

        //get the menu list

        $menuList = $this->menuObj->all();

        return view('admin/menu/index', ['positions' => $positions, 'menu' => $menuList]);
    }

    public function menuList(Request $request) {
        $menuList = $this->menuObj->where('menu_position', '=', $request->post('id'))->orderBy('rank')->get();
        if ($menuList) {
            $this->data['status'] = true;
            $this->data['menuList'] = $menuList;
        }
        return $this->data;

    }

    public function newMenu(Request $request) {

        if ($request->post()) {
            $menuObj = new Menu();
            if ($menuObj->create($request->post())->id) {
                $this->data['status'] = true;
                $this->data['msg'] = "菜单添加成功";
            } else {
                $this->data['msg'] = "菜单添加失败,原因未知, 请联系管理员!";
            }
            return $this->data;


        }
    }

    public function submenuList(Request $request) {
        if (!$request->post()) {
            $this->data['msg'] = '获取菜单失败';
            return $this->data;
        }

        $submenuList = $this->submenuObj->where('menu_id', '=', $request->post('id'))->orderBy('rank')->get();

        if ($submenuList->count()) {
            $this->data['status'] = true;
            $this->data['msg'] = 'success';
            $this->data['submenuList'] = $submenuList;
        } else {
            $this->data['status'] = true;
            $this->data['msg'] = "submenuEmpty";
        }

        return $this->data;
    }

    public function addSubmenu(Request $request) {
        if (!$request->ajax()) {
            return $this->data;
        }

        try {
            $this->submenuObj->create($request->post());
            $this->data['status'] = true;
            $this->data['msg'] = '子菜单添加成功';
        } catch (\Exception $exception) {
            $this->data['msg'] = $exception->getMessage();
        }

        return $this->data;

    }

    public function menuOrder(Request $request) {
        $orderObj = "";
        switch ($request->post('menuName')) {
            case "menuList":
                $menu = "menu";
                $orderObj = $this->menuObj;
                break;
            case "subMenuList":
                $orderObj = $this->submenuObj;
                break;
        }

        $order = explode("_", $request->post('menuOrder'));

        foreach ($order as $key => $value) {
            if ($orderObj->where('id', '=', $value)->update(['rank' => $key])) {
                $this->data['status'] = true;
                $this->data['msg'] = "保存成功";
            }
        }

        return $this->data;


    }

    public function delMenu(Request $request) {

        switch ($request->post('type')) {
            case 0:
                $obj = $this->menuObj;
                $this->submenuObj->where('menu_id','=',$request->post('id'))->delete();
                break;
            case 1:
                $obj = $this->submenuObj;
                $menu = $obj->find($request->post('id'));
                $this->data['menuId'] = $menu->menu_id;
                $this->data['menuName']= $this->menuObj->find($this->data['menuId'])->menu_name;


                break;
        }



        if($obj->destroy($request->post('id'))){
            $this->data['status'] = true;
            $this->data['msg'] = "菜单删除成功";
        }
        return $this->data;


    }

}
