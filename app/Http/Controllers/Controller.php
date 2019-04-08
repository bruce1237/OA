<?php

namespace App\Http\Controllers;

use App\Model\Order;
use App\Model\OrderStatus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected  $modalFullNameSpace = "App\Model\\";
    protected $returnData =[
        'status'=>false,
        'msg' => 'init',
        'code' => 2,
        'data'=>null,
    ];

    public function runTimer(string $process)
    {
        $this->log([
            $process,
            microtime(true),
        ], "timer");
    }


    /**
     * log system  record logs
     * @param array $logData
     * @param string $type
     */
    public function log(array $logData, $type = "log")
    {
        $time = date("Y-m-d H:i:s");
        array_unshift($logData, $time);
        $log = array_map('json_encode', $logData);
        $fileName = "{$type}.log";
        file_put_contents(storage_path("logs/{$fileName}"), implode("-", $log) . "\r\n", FILE_APPEND);
    }


    protected function authorizeOrderStatusChange(Request $request){
        /**
        "order_id" => "1"
        "order_tax_ref" => "99988877665544"
        "tax_number" => "987654321"
        "tax_received_date" => "2019-03-09"
        "order_settlement" => "已结算"
        "order_settled_date" => "2019-02-28"
        "order_status_code" => "5"
        **/

        $orderStage = Order::find($request->post('order_id'))->order_stage;
        $orderStatusCategory = OrderStatus::find($request->post('order_status_code'))->getOriginal('order_status_category');

        /**
         * how orderStage works
         * Stage 0: can only change to category 0 status
         * stage 1: can only change to category 1 status
         * stage 2: can only change to category 2 status
         **/

        return $orderStage == $orderStatusCategory;





    }


}
