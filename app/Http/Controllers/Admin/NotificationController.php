<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    private $_checker = array(); //like observer, to check if there is any new request need to notify the end user
    private $_clientChangeArr = array(); //used to store the files for client change request
    private $_returnDataTemp = [    //the return data sturcture
        'position_id' => [0],       //notification to position id
        'status' => false,          // indicate there need to notify or not
        'title' => '',              //title of the notify popup window
        'body' => '',               //body of the notify popup window
        'icon' => '',               //icon need to be used for th popup window
        'dir' => 'auto'             //hav no idea todo: need to find out how to use the browser notification function
    ];

    private $_returnData = array(); //the return data

    /**
     * NotificationController constructor.
     * assign necessary checker into the checker array
     * and assign the client change files in the clientChangeArr array
     */
    public function __construct()
    {
        $this->_checker = [
            'changeClientInfo',
        ];
        $this->_clientChangeArr = Storage::disk('CRM')->allFiles("client/change/");
    }

    /**
     * usage: handle all the notification
     * @return array
     *
     */
    public function handler()
    {

        //get staff info for check is the current logged staff need to be notified
        $staff = Staff::find(Auth::guard('admin')->user()->staff_id);
        foreach ($this->_checker as $checker) { //go through the checker
            $data = call_user_func([$this, $checker]); //call each check function to get result

            if(in_array($staff->position_id,$data['position_id'])){ //if the current logged in staff in the returned data's position_id filed, then need to notify the staff
                unset($data['position_id']); //destroy unnecessary variable/array filed
                $this->_returnData[]=$data; //assign the data into the return data
            }
        }

        return $this->_returnData; //return the checking result
    }

    /**
     * usage: check if there is any file in the indicated folder, then their is file need to be notify
     * @return array
     */
    private function changeClientInfo()
    {
        $data=$this->_returnDataTemp;//structure the $data variable
        if ($this->_clientChangeArr) { //if there is any file in the indicated folder, then their is file need to be notify
            $data = [
                'position_id' => explode(',',env('CLIENT_MOBILE_CHANGE_POSITION_NOTIFY')), //list which position need to be notified
                'status' => true,
                'title' => '等待审批',
                'body' => '客户信息修改',
                'icon' => '',
                'dir' => 'auto'
            ];
            $this->_returnDataTemp[]=$data;
        }

        return $data;
    }
}
