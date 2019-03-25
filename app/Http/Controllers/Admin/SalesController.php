<?php

namespace App\Http\Controllers\admin;

use App\Model\Sales;
use App\Model\SalesTarget;
use App\Model\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    //
    protected $targetSet = false;
    protected $data=['status'=>true, 'msg'=>'init'];
    protected $currentMonth;

    public function __construct()
    {
        $this->currentMonth  = date("Y-m");

    }


    public function addSales(Request $request) {
        $postData = $request->post();
        $postData['staff_id'] = Auth::guard('admin')->user()->staff_id;
        $postData['staff_name'] = Auth::guard('admin')->user()->name;
        $postData['date'] = date("Y-m-d");
        $postData['department_id'] = Staff::find($postData['staff_id'])->department_id;

        try {
            $sum = Sales::where('staff_id','=',$postData['staff_id'])->where('date','like',date("Y-m-").'%')->sum('sales')+$postData['sales'];
           try{
            $sales = Sales::where('staff_id', '=', $postData['staff_id'])->where('date', '=', $postData['date'])->first()->sales;
            $postData['sales'] += $sales;
           }catch (\Exception $e){

           }
        } catch (\Exception $exception) {
            $sum=$postData['sales'];

        }


        if ($this->updateSalesTarget($postData['staff_id'], $sum)) {
            if($postData['sales']>0){
                Sales::updateOrCreate(['staff_id' => $postData['staff_id'],'date'=>$postData['date']], $postData);
            }else{
                Sales::where(['staff_id' => $postData['staff_id'],'date'=>$postData['date']], $postData)->delete();
            }
            $this->data =['status'=> true,'msg'=>'更新成功!'];
        }

        return $this->data;


    }

    public function updateSalesTarget($staffId, $achieved) {
        return SalesTarget::where('staff_id', '=', $staffId)->where('month', '=', $this->currentMonth)->update(['achieved' => $achieved]);
    }

    public function monthlySales() {

        $salesTarget = SalesTarget::where('month','=',date($this->currentMonth))->get();
        $sales = Sales::where('date', 'like', date($this->currentMonth)."%")->orderBy('department_id')->orderBy('date')->get();

        $salesRecord = array();

        foreach ($sales as $key => $value) {
            $salesRecord[$value['staff_id']]['staff_name'] = $value['staff_name'];
            $salesRecord[$value['staff_id']]['sales'][] = [$value['date'] => $value['sales']];
            $salesRecord[$value['staff_id']]['department_id'] = $value['department_id'];


            foreach ($salesTarget as $k => $v) {
                if($v['staff_id'] == $value['staff_id']){

                    $salesRecord[$v['staff_id']]['target'] = $v['target']?$v['target']:0;
                    $salesRecord[$v['staff_id']]['achieved'] = $v['achieved']?$v['achieved']:0;
                }
            }


        }
        return $salesRecord;

    }


}
