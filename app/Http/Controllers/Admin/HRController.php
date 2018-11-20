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
        //get department list
        $departments = Department::all();
        $departmentsMaxId = Department::all()->max('id');

        $positions = Position::all();
//        dd(is_array($positions));
        $positionsMaxId = Position::all()->max('id');

        $managers = Staff::where('staff_level',">",0)->get();

        return view('admin/hr/index',['departments'=>$departments,'departmentsMaxId'=>$departmentsMaxId,'positions'=>$positions,'positionsMaxId'=>$positionsMaxId,'managers'=>$managers]);
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
                $position->position_name = $value;
                if($position->save()){
                    $data['status'] = true;
                    $data['msg'] .="$value 修改成功<br />";
                }
            }
        }
        return $data;

    }
}
