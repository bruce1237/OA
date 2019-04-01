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
    private $_trBg = ['table-info',
        'table-primary',
        'table-success',
        'table-info',
        'table-primary',
        'table-success',
        'table-info',
        'table-primary',
        'table-success',
    ];

    //
    public function setTargetIndex()
    {
        //get the staff List for set the sales target
        $staffList = $this->staffList();

        $data = [
            'staffList' => $staffList
        ];

        return view('admin/Staff/setTargetIndex', ['data' => $data]);
    }

    /**
     * get the staff list and last five month of sales target and completion levels for reference
     * when manager need to set up a new sales target
     * @return mixed
     */
    private function staffList()
    {
        //get the staffs' department id which are sales: the department is assignable
        $assignableDepartIds = Department::where('assignable', '=', '1')->select('id')->get()->toArray();
        //get the staffs which are sales
        $staffs = Staff::whereIn('department_id', $assignableDepartIds)
            ->where('staff_status', '=', '1')
            ->orderBy('department_id', 'asc')
            ->groupBy('staff_id')
            ->get();
        //get the month of five month ago
        $FiveMonthAgo = date("Y-m", strtotime(date("Y-m-d") . "-5 month"));
        //init the salesArray
        $salesArr = array();
        //go through the staffs
        foreach ($staffs as $staff) {
            //get each staff's target
            $sales = SalesTarget::where('staff_id', '=', $staff->staff_id)
                ->where('month', '>=', $FiveMonthAgo)
                ->orderBy('month', 'desc')
                ->get();

            //restructure the staff-sales-completion data
            foreach ($sales as $sale) {
                $salesArr[$sale->month]['target'] = $sale->target;
                $salesArr[$sale->month]['achieved'] = $sale->achieved;
                $salesArr[$sale->month]['percentage'] = number_format(($sale->achieved / ($sale->target ? $sale->target : 1)) * 100, 2);
            }
            $staff->sales = $salesArr; //assign the staff-sales-completion data to staff object attribute: sales;
        }
        $staffs->trBg = $this->_trBg; //assign the staff background for department distinguish into staff object attribute: trBg
        //return the staff object
        return $staffs;
    }

    /**
     * update the staff sales target
     * @param Request $request
     * @return array
     */
    public function updateSalesTarget(Request $request)
    {
        // assign post data into local variable:data
        $data = $request->post();
        $currentMonth = date("Y-m"); //get the current month
        try {
            //set each staff's sales target, if the target already exist, then update the target
            foreach ($data as $staff_id => $target) {
                SalesTarget::updateOrCreate(['staff_id' => $staff_id, 'month' => $currentMonth], ['target' => $target]);
            }
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "更新成功";
            $this->returnData['code'] = 1;
        } catch (\Exception $e) {
            $this->returnData['msg'] = "更新失败";
        }
        return $this->returnData;
    }

    /**
     * get the pointed year month sales details
     * @param Request $request
     * @return array
     */
    public function getSalesDetails(Request $request)
    {

        try {
            $sales = Sales::where('staff_id', '=', $request->post('staff_id'))
                ->where('date', 'like', $request->post('month') . "%")
                ->get();
            if (sizeof($sales)) {
                $this->returnData['data'] = $sales;
                $this->returnData['status'] = true;
                $this->returnData['code'] = 1;
            } else {
                $this->returnData['msg'] = "无销售记录";
            }

        } catch (\Exception $e) {
            $this->returnData['msg'] = "获取销售记录失败";
        }
        return $this->returnData;
    }

    /**
     * calculate and show the current month sales figures
     * @return array
     */
    public function currentMonthSales()
    {
        //init
        $currentMonth = date('m'); //current Month in digits
        $currentYear = date('Y'); //current Year in digits
        $currentDate = date('d'); //current day in digits
        $sales = array(); // array use to hold all the sales data which got from the database
        $weekend = [6, 7]; //define witch day is weekend, if the current month has working weekend day, then it's better to set to whole month is workingday
        $workDays = array(); // variable used to hold
        $day_sales = []; //use to store sales categorized by date
        $departId = 0; //init a variable to store department_id


        //construct the workDay array to hold all the weekday in the current month
        for ($i = 1; $i <= $currentDate; $i++) {
            $day = date('N', strtotime("{$currentYear}-{$currentMonth}-{$i}"));
            if (!in_array($day, $weekend)) {
                $workDays[] = $i . "号";
            }
        }

        //get the salesRecords from sales database
        $salesRecords = Sales::where('date', 'like', "{$currentYear}-{$currentMonth}%")
            ->orderBy('department_id', 'asc')
            ->orderBy('date', 'asc')
            ->get();

        //walk through the salesRecords to construct the sales array to store all the sales data
        foreach ($salesRecords as $record) {
            //get the sales target for each staff
            $salesTarget = SalesTarget::where('month', '=', "{$currentYear}-{$currentMonth}")
                ->where('staff_id', '=', $record->staff_id)
                ->first();

            //if the staff has been set up an target
            if ($salesTarget) {
                $sales[$record->staff_id]['staff_name'] = $record->staff_name;
                //in case the target is 0 or empty/null, then can not calculate the achieved percentage, so use 1 instead
                $sales[$record->staff_id]['target'] = $salesTarget->target ? $salesTarget->target : 1; //target for the staff
                $sales[$record->staff_id]['achieved'] = 0; //the staff has achieved amount
                $sales[$record->staff_id]['achievedPect'] = 0; //the staff has achieved percentage
                $sales[$record->staff_id]['depart_achieved'] = 0; //the staff belongs department has achieved amount
                $sales[$record->staff_id]['department_id'] = $record->department_id; //the staff belongs department_id
                $sales[$record->staff_id]['sales'][$record->date] = $record->sales; //restructure the sales use salesdate=>salesFigure format
            }
        }


        //To restructure the day_sales array structure
        //walk through the sales array data to fill in the date which has none sales been made
        foreach ($sales as $key => $sale) {
            $day_sales['total']['target'] = 0; //init day_sales with total['target'] filed value
            $day_sales[$sale['department_id']]['target'] = 0; //init day_sales target categorized by department_id with init value of 0
            for ($i = 1; $i <= $currentDate; $i++) { //go through the month day by day till today
                $day = sprintf("%02d", $i); // format date to two digits figures like 1 => 01
                $loopdate = "{$currentYear}-{$currentMonth}-{$day}"; //loopdate is current month's date increased day by day
                $weekday = date("N", strtotime($loopdate)); // check the loopdate is weekday or weekend
                if (!in_array($weekday, $weekend)) { //if the loopday is weekday
                    $day_sales[$sale['department_id']][$loopdate] = 0; //assign each workday with default sales of 0 categorized by department_id
                    $day_sales['total'][$loopdate] = 0; // assign each workday into day_sales with default sales of 0 categorized by total
                }
            }
            //init day_sales default value:0 for  achieved and achievedPect(achieved percentage)
            // and total categorized achieved and achievedPect
            $day_sales[$sale['department_id']]['achieved'] = 0;
            $day_sales[$sale['department_id']]['achievedPect'] = 0;
            $day_sales['total']['achieved'] = 0;
            $day_sales['total']['achievedPect'] = 0;

        }


        //To restructure the sales figures categorized by staff
        foreach ($sales as $key => $sale) {
            $count = 0;//set count to 0, used for calculate how many days has no sales
            $day_sales[$sale['department_id']]['target'] += $sale['target']; //calculate the total target categorized by department_id, to count the day_sales
            $day_sales['total']['target'] += $sales[$key]['target']; //calculate the total target for the whole company, to count the total

            for ($i = 1; $i <= $currentDate; $i++) { //walk through the current month day by day
                $day = sprintf("%02d", $i); //format the day into two digits figures
                $loopdate = "{$currentYear}-{$currentMonth}-{$day}"; //loopdate is the full date of the month from the day 1 to today
                $weekday = date("N", strtotime($loopdate)); //convert the loopdate into week number
                if (!in_array($weekday, $weekend)) { //if the loopdate is not weekend
                    //check if the staff has made sales in the loopdate
                    if (!key_exists($loopdate, $sale['sales'])) { //the current staff has not been able to make sales on the loopdate
                        $count++; //count for non-sales days
                        // sale field in the sales to hold the sales value, if there is no sales been made, then hold the table background color
                        if ($count == 1) { //if only 1day not made sales, then the background of the table is yellow
                            $sales[$key]['sale'][$loopdate] = "yellow";
                        } elseif ($count > 1 && $count <= 2) {//if 2days on the ran of no sales been made, then background of the table is red
                            $sales[$key]['sale'][$loopdate] = "red";
                        } else { //for more than 2 days none sales been made, then background of the table is black
                            $sales[$key]['sale'][$loopdate] = "black";
                        }
                    } else { // there is a sales has been made in the current loopdate

                        $sales[$key]['sale'][$loopdate] = $sales[$key]['sales'][$loopdate];//assign the sales into sales[staff_id]['sale']=[loopdate=>sales] fomat
                        $sales[$key]['achieved'] += $sales[$key]['sales'][$loopdate]; // assign the total sales for the staff into the sales array
                        $day_sales[$sale['department_id']][$loopdate] += $sales[$key]['sales'][$loopdate]; //assign the total sales by the loopdate into day_sales by department_id
                        $day_sales[$sale['department_id']]['achieved'] += $sales[$key]['sales'][$loopdate]; //assign the total achieved by the loopdate into day_sales by department_id
                        $day_sales['total']['achieved'] += $sales[$key]['sales'][$loopdate]; //calculate total achieved in the company on the loopdate
                        $day_sales['total'][$loopdate] += $sales[$key]['sales'][$loopdate]; //calculate total sales made in the company on the loopdate
                        $count = 0; //if there is a sales been made on the loopdate, then reset the counter
                    }
                }
            }

            //after walk through the month, now need to calculate the achieved percentage
            //achieved percentage by department
            $day_sales[$sale['department_id']]['achievedPect'] = round($day_sales[$sale['department_id']]['achieved'] * 100 / $day_sales[$sale['department_id']]['target'], 2);
            //achieved percentage in total of the company
            $day_sales['total']['achievedPect'] = round($day_sales['total']['achieved'] * 100 / $day_sales['total']['target'], 2);
            //achieved percentage by staff_id
            $sales[$key]['achievedPect'] += round(($sales[$key]['achieved'] / $sales[$key]['target']) * 100, 2);

            //use to check if the current staff is in the same department of the previous staff
            if (!$departId) { //init, this is the first staff going through, the department_id with the init value of 0
                $departId = $sale['department_id']; //assign the current/first staff's department_id into the variable departId to hold the data
                $sales[$key]['new'] = 0; // as the first staff, so they are in the same department as the previous one which is no one
            } else { // the departId not with the init value of 0, which means this is not the first staff
                if ($departId != $sale['department_id']) {  //the current staff is not in the same department of the previous staff, so
                    $sales[$key]['new'] = 1; //set the flag to new
                    $departId = $sale['department_id']; //assign the current staff department_id into the variable depardId
                } else {
                    $sales[$key]['new'] = 0; //the current staff is in the same department of the previous staff, so set the new flag to 0/false
                }
            }
            unset($sales[$key]['sales']); //finished restructure the sales array, so unset the original sales filed to make the array tidy
        }
        //assign the necessary data into a single array: $data
        $data = ['sales' => $sales, 'trBg' => $this->_trBg, 'workDays' => $workDays, 'daySales' => $day_sales];
//        dd($data);
        return $data;
    }
}
