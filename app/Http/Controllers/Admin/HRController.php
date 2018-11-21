<?php

namespace App\Http\Controllers\Admin;

use App\Model\Department;
use App\Model\Position;
use App\Model\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HRController extends Controller
{
    //
    public function index(){
        //get staff list
        $staffList = Staff::all(['staff_id','staff_name','staff_dob','staff_mobile_work','staff_mobile_private','staff_email_private','staff_id_no','staff_wenxin_work']);


        //get department list
        $departments = Department::all();
        $departmentsMaxId = Department::all()->max('id');

        $positions = Position::all();
//        $positions = Position::find(1);
//        dd($positions->position_rank);
        $positionsMaxId = Position::all()->max('id');

        $managers = Staff::where('staff_level',">",0)->get();

        return view('admin/hr/index',['departments'=>$departments,'departmentsMaxId'=>$departmentsMaxId,'positions'=>$positions,'positionsMaxId'=>$positionsMaxId,'managers'=>$managers,'staffList'=>$staffList]);
    }

    public function newDepart(Request $request){

        $data['status'] = false;
        $data['msg'] = 'init';
        if(!$request->has('depart_name') || empty($request->post('depart_name'))){
            $data['msg']="部门名称不能为空";
            return $data;
        }
        if(Department::create($request->post())){
            $data['status'] = true;
            $data['msg'] = "部门创建成功！";
            return $data;
        }
    }

    public function modifyDepart(Request $request){
        $data['status'] = false;
        $data['msg']='init';

        foreach ($request->post() as $key => $value) {
            if($value==null){
                if(Department::destroy($key)){
                    $data['status'] = true;
                    $data['msg']="删除成功！";
                }
            }else{
                $dep = Department::find($key);
                $dep->depart_name = $value;
                if($dep->save()){
                 $data['status'] = true;
                    $data['msg']="修改成功！";
                }
            }
        }
        return $data;

    }

    public function newPosition(Request $request){
        $data['status'] = false;
        $data['msg'] = "init";
//        dd($request->post());

        if(!$request->post('position_name')){
            $data['msg'] = "职位名称不能为空";
            return $data;
        }

        if(Position::create($request->post())){
            $data['status'] = true;
            $data['msg'] = "职位添加成功";
            return $data;
        }

    }

    public function modifyPositions(Request $request){
        $data['status'] = false;
        $data['msg'] = '';


        foreach ($request->post() as $key => $value){
            if($value == null){
                if(Position::destroy($key)){
                    $data['status'] = true;
                    $data['msg'] .="删除成功<br />";
                }
            }else{
                $position = Position::find($key);
                $position_name = explode('/',$value);
                $position->position_name = $position_name[0];
                $position->position_rank = $position_name[1];
                if($position->save()){
                    $data['status'] = true;
                    $data['msg'] .="$value 修改成功<br />";
                }
            }
        }
        return $data;

    }

    public function getManagers(Request $request){
        //get staff rank
        $positionRank = Position::where('id','=',$request->post('position_id'))->orderBy('position_rank')->first()->getOriginal('position_rank');
//        $hierarchy = Position::where('position_rank',">",$positionRank)->select('id')->get();
        $managers = Staff::where('staff_level','>',$positionRank)->get();
        return json_encode($managers);
    }

    public function getStaffLevel(Request $request){
        $staffLevel = Position::where('id','=',$request->post('position_id'))->first()->getOriginal('position_rank');
        return json_encode($staffLevel);
    }

    public function newStaff(Request $request){
        $data['status'] = false;
        $data['msg'] = "init";
        $postData=[];

        if(!$request->file('upload_staff_photo')){
            $data['msg'] = "请填写必要项";
            $data['emptyCols'] = ['upload_staff_photo'];
            return json_encode($data);
        }else{
            $photoFullName = uniqid('ML_').".".$request->file('upload_staff_photo')->getClientOriginalExtension();
            $request->file('upload_staff_photo')->storeAs('photo',$photoFullName,'staff');

        }

        if(in_array(null, $request->post()) || in_array("null", $request->post())){
            $emptyCols1 = array_keys($request->post(),null);
            $emptyCols2 = array_keys($request->post(),"null");
            $emptyCols = array_merge($emptyCols1,$emptyCols2);

            $data['msg'] = "请填写必要项";
            $data['emptyCols'] = $emptyCols;
            return json_encode($data);
        }

        $postData =$request->post();
        $postData['staff_photo'] = $photoFullName;


        try{
            Staff::create($postData);
            $data['status'] = true;
            $data['msg'] = "员工添加成功!";
        }catch (\Exception $exception){
            switch ($exception->getCode()){
                case 23000:
                    $data['msg'] = "员工号码已经存在!";
                    break;
            }
        }
        return $data;

    }

    public function staff($id){
        $data['status'] = false;
        $data['msg'] = "init";
       $staff = Staff::find($id);
       if($staff){
           $data['status'] = true;
           $data['staff'] = $staff;
       }
       return $data;
    }

}
