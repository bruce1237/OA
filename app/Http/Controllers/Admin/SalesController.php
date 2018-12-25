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

    public function addSales(Request $request) {
        $postData = $request->post();
        $postData['staff_id'] = Auth::guard('admin')->user()->staff_id;
        $postData['staff_name'] = Auth::guard('admin')->user()->name;
        $postData['date'] = date("Y-m-d");
        $postData['department_id'] = Staff::find($postData['staff_id'])->department_id;

        try {

            $sales = Sales::where('staff_id', '=', $postData['staff_id'])->where('date', '=', $postData['date'])->first()->sales;
//            $sales = Sales::where('staff_id','=',78)->where('date','=',$postData['date'])->first()->sales;
            $postData['sales'] += $sales;
        } catch (\Exception $exception) {

        }


        if ($this->updateSalesTarget($postData['staff_id'], $postData['sales'])) {
            Sales::updateOrCreate(['staff_id' => $postData['staff_id']], $postData);
        }


    }

    public function updateSalesTarget($staffId, $achieved) {
        $month = date("Y-m");
        return SalesTarget::where('staff_id', '=', $staffId)->where('month', '=', $month)->update(['achieved' => $achieved]);
    }

    public function monthlySales() {

        $salesTarget = SalesTarget::where('month','=',date("Y-m"))->get();
        $sales = Sales::orderBy('department_id')->orderBy('date')->get();

        $salesRecord = array();

        foreach ($sales as $key => $value) {
            $salesRecord[$value['staff_id']]['staff_name'] = $value['staff_name'];
            $salesRecord[$value['staff_id']]['sales'][] = [$value['date'] => $value['sales']];


            foreach ($salesTarget as $k => $v) {
                $salesRecord[$v['staff_id']]['target'] = $v['target'];
                $salesRecord[$v['staff_id']]['achieved'] = $v['achieved'];
            }


        }

//dd($salesRecord);
        return $salesRecord;

    }


}
