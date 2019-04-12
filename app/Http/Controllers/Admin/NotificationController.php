<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller {
    private $_checker = array(); //like observer, to check if there is any new request need to notify the end user
    private $_clientChangeArr = array(); //used to store the files for client change request
    private $_returnData = [    //the return data sturcture
        'notify'=>false,
        'title' => '等待您的审批',              //title of the notify popup window
        'body' => '',               //body of the notify popup window
        'icon' => '',               //icon need to be used for th popup window
        'dir' => 'auto'             //hav no idea todo: need to find out how to use the browser notification function
    ];



    /**
     * NotificationController constructor.
     * assign necessary checker into the checker array
     * and assign the client change files in the clientChangeArr array
     */
    public function __construct() {
        $this->_checker = [
            'changeClientInfo',
            'pendingOrders',
        ];
        $this->_clientChangeArr = Storage::disk('CRM')->allFiles("client/change/");


    }

    /**
     * usage: handle all the notification
     * @return array
     *
     */
    public function handler() {
        $staff = Staff::find(Auth::guard('admin')->user()->staff_id);
        //get staff info for check is the current logged staff need to be notified

        foreach ($this->_checker as $checker) { //go through the checker
            call_user_func([$this, $checker],$staff->position_id); //call each check function to get result
        }

        return $this->_returnData;

    }

    /**
     * usage: check if there is any file in the indicated folder, then their is file need to be notify
     * @return array
     */
    private function changeClientInfo($positionId) {
        $notifyPositionId =explode(',', env('CLIENT_MOBILE_CHANGE_POSITION_NOTIFY')); //list which position need to be notified
        if(in_array($positionId,$notifyPositionId)){
            if ($this->_clientChangeArr) { //if there is any file in the indicated folder, then their is file need to be notify
                $this->_returnData['body'] .= " 客户信息修改 \r\n";
                $this->_returnData['nofify'] =true;
            }
        }
    }

    private function pendingOrders($positionId) {
        $notifyPositionId =explode(',', env('PENDING_ORDER_POSITION_NOTIFY')); //list which position need to be notified
        if(in_array($positionId,$notifyPositionId)) {
            $orders = Order::where('order_status_code', '=', '0')->count();
            if($orders){
                $this->_returnData['body'] .= " 共有{$orders}订单等待您的审批 \r\n";
            }
                $this->_returnData['nofify'] =true;
        }
    }
}
