<?php

namespace App\Http\Controllers\admin;

use App\Model\Func;
use App\Model\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AccessController
 * @package App\Http\Controllers\admin
 * @target: assign access authorize setup
 * @brief:
 *      this section has 2 sections:
 *      1: input all the controllers and functions
 *      into a neutral table, so can be used as an
 *      reference when assign access
 *      2: assign access to each position
 */
class AccessController extends Controller
{
    // set up common ajax return variable: icon =2 is X(cross), 1 is V(tick)
    protected $data = ['status' => false, 'msg' => 'init', 'icon' => 2];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * show the main page of the access Control
     * along with available staff Positions and get the whole list of neutral access
     *
     * NOTE:: All neutral controllers has been assign to position 0
     *
     */
    public function index() {

        //get all positions from database
        $positions = Position::all();

        //get all the neutral controllers which position is 0
        $controllers = \App\Model\Controller::where('position_id', '=', 0)->get();

        //define a empty KV array to hold controller and functions in order
        $accessControllers = [];

        foreach ($controllers as $controller) {
            //assign function name to the holding array in the following structure
            // array['controllerName']= function objects which belongs to the controller
            $accessControllers[$controller['controller']] = Func::where('controller_id', '=', $controller['id'])->get();
        }

        return view('admin/Access/index', ['positions' => $positions, 'accessControllers' => $accessControllers]);
    }

    /**
     * @param Request $request
     * @return array
     * @target: get controllers which related to the specified position
     */
    public function getControllers(Request $request) {

        //get position id by post
        $position_id = $request->post('position_id');

        //get controllers obj from database which belongs to the position id
        $controllers = \App\Model\Controller::where('position_id', '=', $position_id)->get();

        //rewrite the return variable
        $this->data = ['status' => true, 'msg' => $controllers];

        return $this->data;
    }

    /**
     * @param Request $request
     * @return array
     * @target: add Controller with assigned position id into database
     */
    public function addController(Request $request) {
        //$request contains: positionId, controllerName

        //insert into database,
        if (\App\Model\Controller::create($request->post())) {

            //insert success, rewrite return variable
            $this->data = ['status' => true, 'msg' => '控制器添加成功', 'icon' => 1];
        }

        return $this->data;
    }

    /**
     * @param Request $request
     * @return array
     * @target: get indicated controller's functions and the controller name
     */
    public function getFuncs(Request $request) {
        //get the specified controller's name
        $controllerName = \App\Model\Controller::find($request->post('controller_id'))->controller;

        //get the functions belongs to the specified controllers
        $funcs = Func::where('controller_id', '=', $request->post('controller_id'))->get();

        //rewrite the return variable
        $this->data = ['status' => true, 'msg' => $funcs, 'icon' => 1, 'controllerName' => $controllerName];
        return $this->data;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function addFunc(Request $request) {
        //$request contains: controllerID, functionName,

        //update or create function which belongs to the controller by controller_id
        Func::updateOrCreate($request->post());

        //rewrite return variable
        $this->data = ['status' => true, 'msg' => '方法添加成功', 'icon' => 1];

        return $this->data;
    }

    /**
     * @param Request $request
     * @return array
     * @target: add neutral controller and it's functions for reference use
     */
    public function addCommonControllerFuncs(Request $request) {

        //check if the controller exist
        $controller = \App\Model\Controller::where('controller', '=', $request->post('controller'))->where('position_id', '=', 0)->first();

        if (!$controller) { //controller not exist
            //insert the controller into database and get the controller id from that insert action
            $controller_id = \App\Model\Controller::insertGetId(['controller' => $request->post('controller')]);
        } else { //controller exist
            //controller exist, so get the controller from the $controller obj
            $controller_id = $controller->id;
        }

        //empty the functions which belongs to that controller id, get ready for the next insertion
        Func::where('controller_id', '=', $controller_id)->delete();

        //assign the post data into a variable
        $postData = $request->post();

        //****clear the data and get ready for database import****

        //first unset the unnecessary data
        unset($postData['controller']);

        //set up a variable to hold the data which wants to import to database
        $func = [];

        //as the posted data base twice amount of data because of the way the data been post, so restructure the data for database
        for ($i = 0; $i < sizeof($postData) / 2; $i++) {

            //as the template has 20 slot and may not be fully used, so check if the data is exist
            if ($postData['func' . $i]) { //data exist
                //push the data into the the correct structure and ready for database import
                $func[] = ['function' => $postData['func' . $i], 'controller_id' => $controller_id, 'comment' => $postData['for' . $i]];
            }
        }

        //insert the data into database table: functions
        if (Func::insert($func)) { //insert successful
            //rewrite the return variable
            $this->data = ['status' => true, 'msg' => '添加成功', 'icon' => 1];
        }

        return $this->data;
    }

    /**
     * @return array
     * @target: get all the neutral controller list
     */
    public function getAllControllers() {
        //get controller from the database which has position assigned as 0
        $controllerList = \App\Model\Controller::where('position_id', '=', 0)->get();

        //rewrite the return variable
        $this->data = ['status' => true, 'msg' => $controllerList, 'icon' => 1];

        return $this->data;
    }

    /**
     * @param Request $request
     * @target: delete the controller along with it's functions or just delete the functions
     */
    public function delCF(Request $request) {
        //$request has: type:  0: controller, 1: functions; id: controllerID/functionsID

        //check the postdata id is controller or function
        if (!$request->post('type')) { //0, so this is a controller id
            //delete the controller from the database
            \App\Model\Controller::destroy($request->post('id'));
            //delete this functions from database
            Func::where('controller_id', '=', $request->post('id'))->delete();
        } else { //1: is just the function
            //delete the function from database
            Func::destroy($request->post('id'));
        }

        //rewrite return variable
        $this->data=['status'=>true,'msg'=>'删除成功','icon'=>1];

        return $this->data;
    }
}
