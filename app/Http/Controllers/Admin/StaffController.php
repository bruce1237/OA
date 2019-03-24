<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Model\Department;
use App\Model\Staff;

class StaffController extends Controller
{
    private $_where = array();
    private $_orderBy = array();
    private $_groupBy = array();
    private $_data = array();

    //
    public function setTargetIndex()
    {
        $staffList = $this->staffList();

        $data =[
            'staffList'=>$staffList
        ];

        return view('admin/Staff/setTargetIndex',['data'=>$data]);
    }

    private function staffListABD(){
        //get staff list by department
        $this->_orderBy = ['department_id', 'asc'];
        $this->_where[] = ['key' => 'assignable',
            'op' => '=',
            'value' => '1'];

        $assignableDepartments = $this->getList("App\Model\Department");
        foreach ($assignableDepartments as $department) {
            $assignableDepartmentsIds[] = $department->id;
        }

        $this->_orderBy = ['department_id', 'asc'];
        $this->_where = [
            ['key' => 'department_id',
                'op' => 'in',
                'value' => $assignableDepartmentsIds,
            ],
            ['key' => 'staff_status',
                'op' => '=',
                'value' =>'1',
            ]
        ];

        return  $this->getList('App\Model\Staff');
    }

    private function getListABD($model)
    {
        $where = $this->_where;
        $orderBy = $this->_orderBy;
        $groupBy = $this->_groupBy;


        $result = $model::where(function ($query) use ($where, $orderBy, $groupBy) {
            if (isset($where) && null != $where) {
                foreach ($where as $condition) {
                    switch ($condition['op']) {
                        case "in":
                            $query->whereIn($condition['key'], $condition['value']);
                            break;
                        default:
                            $query->where($condition['key'], $condition['op'], $condition['value']);
                            break;
                    }
                }
            }
        })->get();
        $this->_where = $this->_orderBy = $this->_groupBy = array();

        return $result;

    }



    private function staffList()
    {
        $assignableDepartIds = Department::where('assignable','=','1')->select('id')->get()->toArray();
        $staffs = Staff::whereIn('department_id',$assignableDepartIds)
            ->orderBy('department_id','asc')->get();



        $staffs->trBg=['table-info',
            'table-primary',
            'table-success',
            'table-info',
            'table-primary',
            'table-success',
            'table-info',
            'table-primary',
            'table-success',
        ];




        return $staffs;


    }
}
