<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Model\Department;
use App\Model\Sales;
use App\Model\SalesTarget;
use App\Model\Staff;
use Illuminate\Http\Request;

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
            ->where('staff_status','=','1')
            ->orderBy('department_id','asc')
            ->groupBy('staff_id')
            ->get();
        $FiveMonthAgo = date("Y-m",strtotime(date("Y-m-d")."-5 month"));

        $salesArr=array();
        foreach ($staffs as $staff) {
            $sales = SalesTarget::where('staff_id','=',$staff->staff_id)
                ->where('month','>=',$FiveMonthAgo)
                ->orderBy('month','desc')
                ->get();

            foreach($sales as $sale){
                $salesArr[$sale->month]['target']=$sale->target;
                $salesArr[$sale->month]['achieved']=$sale->achieved;
                $salesArr[$sale->month]['percentage']=number_format(($sale->achieved/($sale->target?$sale->target:1))*100,2);
            }
           $staff->sales = $salesArr;
        }

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

    public function updateSalesTarget(Request $request){
      $data = $request->post();
      $currentMonth = date("Y-m");
      try{
          foreach ($data as $staff_id => $target){
              SalesTarget::updateOrCreate(['staff_id'=>$staff_id,'month'=>$currentMonth],['target'=>$target]);
          }
          $this->returnData['status'] = true;
          $this->returnData['msg'] = "更新成功";
          $this->returnData['code'] =1;
      }catch(\Exception $e){
          $this->returnData['msg'] = "更新失败";
      }
      return  $this->returnData;
    }

    public function getSalesDetails(Request $request){
        try{
            $sales = Sales::where('staff_id','=',$request->post('staff_id'))
                ->where('date','like',$request->post('month')."%")
                ->get();
           if(sizeof($sales)){
               $this->returnData['data']=$sales;
               $this->returnData['status']=true;
               $this->returnData['code']=1;
           }else{
               $this->returnData['msg']="无销售记录";
           }

        }catch(\Exception $e){
            $this->returnData['msg']="获取销售记录失败";
        }
        return $this->returnData;
    }
//    public function currentMonthSales(){
//        $currentMonthOnly = date('m');
//        $currentMonthOnly = date('01');
//        $currentMonth = date("Y-m");
//      $currentMonth = date("Y-01");
//
//        $sales = Sales::where('date','like',$currentMonth."%")
//            ->orderBy('department_id')
//            ->orderBy('date','asc')
//            ->get();
//
//
//        $salesArr =array();
//
//        foreach ($sales as  $key=>$sale){
//            $salesArr[$sale->staff_id]['staff_id'] = $sale->staff_id;
//            $salesArr[$sale->staff_id]['staff_name'] = $sale->staff_name;
//            $salesArr[$sale->staff_id]['depart_id'] = $sale->department_id;
//            $salesArr[$sale->staff_id]['target'] = SalesTarget::where('staff_id','=',$sale->staff_id)->where('month','=',$currentMonth)->first()->target;
//            $salesArr[$sale->staff_id]['sales'][$sale->date]=$sale->sales;
//
//        }
//
//
//
//
//        $count=1;
//        $workdate=array();
//        $workdateFull=array();
//        $depart_id=0;
//
//        foreach ($salesArr as $key =>$staff){
//            $salesArr[$key]['achieved']=0;
//            $salesArr[$key]['achievedPercentage'] = 0;
//            for($i=2;$i<=date('d');$i++) {
//                $formatI = sprintf("-%02d", $i);
//                $date = $currentMonth . $formatI;
//                $dayofweek = date("N", strtotime($date));
//
//                if ($dayofweek != 6 && $dayofweek != 7) {
//
//                        if(!key_exists($date,$staff['sales'])) {
//                            $count++;
//                        }else {
//                            $salesArr[$key]['achieved'] +=$staff['sales'][$date];
//                            $count = 1;
//                        }
//
//                            if($count==1){
//                                $bg='bg-success';
//                            }elseif($count>1 && $count<=2){
//                                $bg='bg-warning';
//                            }elseif($count>2 && $count<=3){
//                                $bg='bg-danger';
//                            }else{
//                                $bg='bg-dark';
//                            }
//
//
//                        $salesArr[$key]['sale'][$date]=key_exists($date,$staff['sales'])?$staff['sales'][$date]:$bg;
//
//                            $workdate[sprintf("%02d", $i)]=0;
//
//
//                        unset($salesArr[$key]['sales']);
//                    }
//
//                }
//            $salesArr[$key]['achievedPercentage'] = round(($salesArr[$key]['achieved']/$salesArr[$key]['target'])*100,2);
//            if($staff['depart_id']==$depart_id){
//
//            }
//            }
//
//dd($salesArr);
//
//        return view('admin/Staff/currentMonthSales',['sales'=>$salesArr,'workdate'=>$workdate]);
//
//    }
}
