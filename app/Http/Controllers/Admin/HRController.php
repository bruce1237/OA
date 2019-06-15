<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\Department;
use App\Model\Position;
use App\Model\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class HRController extends Controller
{

    //initialize the return variable with default value
    protected $data = ['status' => false, 'msg' => 'init', 'icon' => 2];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @target: display the hr page with some preloaded variables
     */
    public function index()
    {
        //get staff list with predesigned
        $staffList = Staff::select('staff_id','staff_no', 'staff_name', 'staff_dob', 'staff_mobile_work', 'staff_mobile_private', 'staff_email_private', 'staff_id_no', 'staff_join_date', 'staff_wenxin_work')->get();

        //get total active staff count
        $staffCount = Staff::count();

        //get department list
        $departments = Department::all();

        //get maxId of departments for front end to list all the depart (for loop)
        $departmentsMaxId = Department::all()->max('id');

        //get all the positions
        $positions = Position::all();

        //get maxId of positions for the front end to list all the position (for loop)
        $positionsMaxId = Position::all()->max('id');

        //get list of managers which staff is greater than 0
        $managers = Staff::where('staff_level', ">", 0)->get();

        //display the page
        return view('admin/hr/index', ['departments' => $departments, 'departmentsMaxId' => $departmentsMaxId, 'positions' => $positions, 'positionsMaxId' => $positionsMaxId, 'managers' => $managers, 'staffList' => $staffList, 'staffCount' => $staffCount]);
    }

    public function activeStaff()
    {
        //get staff list with predesigned
        $staffList = Staff::select('staff_id','staff_no', 'staff_name', 'staff_dob', 'staff_mobile_work', 'staff_mobile_private', 'staff_email_private', 'staff_id_no', 'staff_join_date', 'staff_wenxin_work')
            ->where('staff_status', '=', '1')
            ->get();

        //get total active staff count
        $staffCount = Staff::count();

        //get department list
        $departments = Department::all();

        //get maxId of departments for front end to list all the depart (for loop)
        $departmentsMaxId = Department::all()->max('id');

        //get all the positions
        $positions = Position::all();

        //get maxId of positions for the front end to list all the position (for loop)
        $positionsMaxId = Position::all()->max('id');

        //get list of managers which staff is greater than 0
        $managers = Staff::where('staff_level', ">", 0)->get();

        //display the page
        return view('admin/hr/index', ['departments' => $departments, 'departmentsMaxId' => $departmentsMaxId, 'positions' => $positions, 'positionsMaxId' => $positionsMaxId, 'managers' => $managers, 'staffList' => $staffList, 'staffCount' => $staffCount]);
    }

    public function deactiveStaff()
    {
        //get staff list with predesigned
        $staffList = Staff::select('staff_id', 'staff_no', 'staff_name', 'staff_dob', 'staff_mobile_work', 'staff_mobile_private', 'staff_email_private', 'staff_id_no', 'staff_join_date', 'staff_wenxin_work')
            ->where('staff_status', '=', '0')
            ->get();

        //get total active staff count
        $staffCount = Staff::count();

        //get department list
        $departments = Department::all();

        //get maxId of departments for front end to list all the depart (for loop)
        $departmentsMaxId = Department::all()->max('id');

        //get all the positions
        $positions = Position::all();

        //get maxId of positions for the front end to list all the position (for loop)
        $positionsMaxId = Position::all()->max('id');

        //get list of managers which staff is greater than 0
        $managers = Staff::where('staff_level', ">", 0)->get();

        //display the page
        return view('admin/hr/index', ['departments' => $departments, 'departmentsMaxId' => $departmentsMaxId, 'positions' => $positions, 'positionsMaxId' => $positionsMaxId, 'managers' => $managers, 'staffList' => $staffList, 'staffCount' => $staffCount]);
    }

    /**
     * @param Request $request
     * @return array
     * @target: setup an new department
     */
    public function newDepart(Request $request)
    {
        //$request: dapart_name

        //validation: the department name can not be empty
        if (!$request->has('depart_name') || empty($request->post('depart_name'))) { //the department is empty
            //rewrite the return variable
            $this->data['msg'] = "部门名称不能为空";


            return $this->data;
        }

        //passed the validation
        if (Department::create($request->post())) { //insert into database

            //rewrite the return variable
            $this->data = ['status' => true, 'msg' => "部门创建成功！", 'icon' => 1];

            return $this->data;
        }
    }

    /**
     * @param Request $request
     * @return array
     * @target modify the specified department
     */
    public function modifyDepart(Request $request)
    {
        //$request: all the department name in an array

        //dismantle the array into KV array
        foreach ($request->post() as $key => $value) { //$key as array index, $value as department_name

            if ($value == null) { //the department valule == null, then delete the department
                if (Department::destroy($key)) { //delete the department
                    //rewrite the return variable
                    $this->data = ['status' => true, 'msg' => "删除成功！", 'icon' => 1];
                }
            } else { //the department_name actually has value
                //get the department obj
                $dep = Department::find($key);

                //update the department name
                $dep->depart_name = $value;

                if ($dep->save()) { //update success
                    //rewrite the return variable
                    $this->data = ['status' => true, 'msg' => "修改成功！", 'icon' => 1];
                }
            }
        }
        return $this->data;
    }

    /**
     * @param Request $request
     * @return array
     * @target: setup new position
     */
    public function newPosition(Request $request)
    {
        //$request: position_name, position_rank

        //post data validation
        if (!$request->post('position_name')) { //position_name is empty
            //rewirte the return variable
            $this->data['msg'] = "职位名称不能为空";

            return $this->data;
        }

        //insert into database through ORM
        if (Position::create($request->post())) { //insert success

            //rewrite the return variable
            $this->data = ['status' => true, 'msg' => "职位添加成功！", 'icon' => 1];

            return $this->data;
        }
    }

    /**
     * @param Request $request
     * @return array
     * @target
     */
    public function modifyPosition(Request $request)
    {
        //$request : array array_index => position_name/position_rank format

        //dismantle the post data $key as array index, $value as position_name/position_rank string
        foreach ($request->post() as $key => $value) {

            //explode the string by "/" into position_name and position_rank
            $position_name = explode('/', $value);

            if ($position_name[0] == null) { //position_name is empty
                if (Position::destroy($key)) { //delete the position

                    //rewrite the return variable with append data of msg
                    $this->data['status'] = true;
                    $this->data['msg'] .= "删除成功<br />";
                    $this->data['icon'] = 1;
                }
            } else { //the position_name is not empty
                //get the position obj from database by id
                $position = Position::find($key);

                //allocate positon_name and position_rank into position Object
                $position->position_name = $position_name[0];
                $position->position_rank = $position_name[1];

                //update the database
                if ($position->save()) { //update success

                    //rewrite the return variable append the indicator of which position has been modified
                    $this->data['status'] = true;
                    $this->data['msg'] .= "$value 修改成功<br />";
                    $this->data['icon'] = 1;
                }
            }
        }
        return $this->data;
    }

    /**
     * @param Request $request
     * @return string
     * @target get all the manager from database which staff_level is greater than 0
     */
    public function getManagers(Request $request)
    {
        //$request: department_id, position_id

        //get that position's position_rank  use getOriginal because of the model has used the assessor, so use getOriginal to get the original value
        $positionRank = Position::where('id', '=', $request->post('position_id'))->orderBy('position_rank')->first()->getOriginal('position_rank');

        //get the staffs whos position_rank is greater than that position rank
        $managers = Staff::where('staff_level', '>', $positionRank)->get();

        //return the managers
        return $managers;
    }

    /**
     * @param Request $request
     * @return string
     * @target get staff level from database
     */
    public function getStaffLevel(Request $request)
    {
        //$request: position_id

        //get staffLevel by using position id from database, also as the assessor has been used, so use the getOriginal to get the original data
        $staffLevel = Position::where('id', '=', $request->post('position_id'))->first()->getOriginal('position_rank');

        return $staffLevel;
    }

    /**
     * @param Request $request
     * @return mixed
     * @target add new staff
     */
    public function newStaff(Request $request)
    {


        /**
         * has been deactived as the requirement has been changed
         * staff photo are no longer compulsory
         */

        //        if (!$request->post('staff_id')) {
        //            //新添加员工信息
        //            if (!$request->file('upload_staff_photo')) {
        //                $data['msg'] = "请填写必要项";
        //                $data['emptyCols'] = ['upload_staff_photo'];
        //                return json_encode($data);
        //            }
        //        }

        //put the post data into temp variable for validation puropose
        $dataCheck = $request->post();

        //unset the unnecessary filed so later on do not need to validate
        unset($dataCheck['staff_id']);
        unset($dataCheck['staff_wenxin_private']);
        unset($dataCheck['staff_email_private']);
        unset($dataCheck['staff_address']);
        unset($dataCheck['staff_wenxin_work']);
        unset($dataCheck['staff_salary']);
        unset($dataCheck['staff_commission_rate']);
        unset($dataCheck['staff_contract_no']);
        unset($dataCheck['staff_contract_start']);
        unset($dataCheck['staff_contract_end']);
        unset($dataCheck['staff_family_member']);
        unset($dataCheck['staff_hobby']);
        unset($dataCheck['staff_self_assessment']);
        unset($dataCheck['staff_assessment']);
        unset($dataCheck['staff_edu_history']);
        unset($dataCheck['staff_work_exp']);
        unset($dataCheck['staff_achievement']);

        //validate the post data
        if (in_array(null, $dataCheck) || in_array("null", $dataCheck)) {

            //check if the compulsory field si null or empty
            $emptyCols1 = array_keys($dataCheck, null);
            $emptyCols2 = array_keys($dataCheck, "null");

            //merge the null and empty check result into one array
            $emptyCols = array_merge($emptyCols1, $emptyCols2);

            //rewrite the return variable
            $this->data = ['status' => false, 'msg' => "请填写必要项！", 'emptyCols' => $emptyCols, 'icon' => 2];

            return $this->data;
        }

        //put the post data into an variable

        $postData = $request->post();

        //if a staff photo has been upload
        if ($request->file('upload_staff_photo')) {
            //generate the photoFullName with extension
            $photoFullName = uniqid('ML_') . "." . $request->file('upload_staff_photo')->getClientOriginalExtension();

            //save the photo into a designed folder and named as photo Fullname
            $request->file('upload_staff_photo')->storeAs('staff/photo', $photoFullName, 'public');

            //put new photo name into the post data variable
            $postData['staff_photo'] = $photoFullName;
        } else { //there are no photo has been uploaded
            //unset the staff_photo as there is no photo uploaded
            unset($postData['staff_photo']);
        }

        //after the photo has been saved or not, the upload_staff_photo is no longer required
        unset($postData['upload_staff_photo']);
        //        dd($postData);

        try { //failsafe
            //update or insert into the database
            //dd( $postData);
            Staff::updateOrCreate(['staff_id' => $postData['staff_id']], $postData);
            if ($postData['staff_id']) {
                Admin::updateOrCreate(['staff_id' => $postData['staff_id']], ['staff_id' => $postData['staff_id'], 'staff_no' => $postData['staff_no'], 'name' => $postData['staff_name']]);
            }

            //            Admin::where('staff_id','=',$postData['staff_id'])->update(['name'=>$postData['staff_name'],'staff_no'=>$postData['staff_no']]);
            //            $a = Admin::where('staff_id','=',$postData['staff_id'])->get();
            //            dd($a);c

            //            if ($request->file('upload_staff_photo')) {
            //                $request->file('upload_staff_photo')->storeAs('staff/photo', $photoFullName, 'public');
            //            }

            //rewrite the return variable
            $this->data = ['status' => true, 'msg' => "操作成功！", 'icon' => 1];
            //            $this->data = ['status' => true, 'msg' =>$postData['staff_id'], 'icon' => 1];
        } catch (\Exception $exception) {
            // $this->data['msg'] = $exception->getMessage();
            // echo 
            //rewrite the return variable as there is an exception occurred
            //            $data['msg'] = $exception->getMessage();
            switch ($exception->getCode()) {
                case 23000:
                    $this->data['msg'] = "员工号码已经存在!";
                    break;
            }
        }
        return $this->data;
    }

    /**
     * @param $id
     * @return array
     * @target: get the staff info
     */
    public function staff($id)
    {

        //get staff info
        $staff = Staff::find($id);
        if ($staff) { //if the staff exist
            //rewrite the return variable
            $this->data = ['status' => true, 'staff' => $staff];
        }

        return $this->data;
    }

    /**
     * @param null $id
     * @return array
     * @target: delete the staff by staff id
     */
    public function delStaff($id = null)
    {

        if ($id) { //if the id is not empty
            //delete the staff
            if (Staff::destroy($id)) { //staff delet success

                //also delete from the admin login table
                Admin::where('staff_id', '=', $id)->delete();

                //rewrite the return variable
                $this->data = ['status' => true, 'msg' => "删除成功！", 'icon' => 1];
            }
        } else { //id is not set
            //get the id from the route Request
            $request = Request();
            //delete the staff
            if (Staff::destroy($request->post('staffIds'))) { //staff delete success
                //rewrite the return variable
                $this->data = ['status' => true, 'msg' => "删除成功！", 'icon' => 1];
            }
        }
        return $this->data;
    }

    /**
     * @param $id
     * @return array
     * @target: get staff login info prepare for the staff login password set up
     */
    public function getStaffLoginInfo($id)
    {

        //get designed staff info
        $staffLoginInfo = Staff::where('staff_id', '=', $id)->first(['staff_id', 'staff_no', 'staff_name']);

        if ($staffLoginInfo) { //get staff info success

            //rewrite the return variable
            $this->data = ['status' => true, 'info' => $staffLoginInfo];
        }
        return $this->data;
    }

    /**
     * @param Request $request
     * @return array
     * @target assign the password into the admin table or create if not exist
     */
    public function saveStaffLoginInfo(Request $request)
    {

        if (Admin::updateOrCreate(['staff_id' => $request->post('staff_id')], $request->post())) {
            $this->data = ['status' => true, 'msg' => '密码修改成功', 'icon' => 1];
        }

        return $this->data;
    }
}
