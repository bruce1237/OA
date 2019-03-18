<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Client;
use App\Model\Company;
use App\Model\Department;
use App\Model\Firm;
use App\Model\InfoSource;
use App\Model\Staff;
use App\Model\Visit;
use App\Model\VisitStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{

    public function index()
    {
        //get staff level, use staff_level to decided what and how to display required info
        $staffLevel = Staff::find(Auth::guard('admin')->user()->staff_id)->staff_level;
        $staffId = Auth::guard('admin')->user()->staff_id; //get staff id

        $assignableStaffs = Staff::join('departments','departments.id','=','staff.department_id')
            ->where('departments.assignable','=','1')
            ->get();


//        $staffLevel=3; //部门经理级别 测试用

        $clients = $this->allClientList($staffId, $staffLevel); //get the full client according to the staffLevel
        $clientSource = InfoSource::all(); //get infoSource data
        $visitStatus = VisitStatus::all(); //get visitStatus data
        $firms = Firm::all(); //get firm data

        //use one unified combined variable to store all the data for the view use, so the view() statement will be shorter and easier to read
        $data = [
            'staffId' => $staffId,
            'staffLevel' =>$staffLevel,
            'assignableStaffs' => $assignableStaffs,
            'clients' => $clients,
            'firms' => $firms,
            'clientSource' => $clientSource,
            'visitStatus' => $visitStatus,
        ];
        return view('admin/client/index', ['data' => $data]);
    }

    private function allClientList($staffId, $staffLevel)
    {
        $clientList = '';
        switch ($staffLevel) {
            case "0": //普通员工
                $clientList = Client::where('clients.client_assign_to', '=', $staffId)
                    ->where('clients.client_status', '=', '1')
                    ->orderby('updated_at', 'desc')
                    ->get();
                break;
            case "3": //部门经理
                $clientList = Client::where('clients.client_status', '=', '1')->get();
                break;
        }
        foreach ($clientList as $key => $client) {
            $visitNextDate = Visit::where('visit_client_id', '=', $client->client_id)->orderBy('visit_next_date', 'desc')->first();
            $clientList[$key]['visit_next_date'] = $visitNextDate ? $visitNextDate->visit_next_date : '';
            $clientList[$key]['visit_status'] = $visitNextDate ? $visitNextDate->visit_status : '';
        }

        $clients['list_name'] = '全部客户信息';
        $clients['bg'] = 'bg-info';
        $clients['clients'] = $clientList;
        return $clients;
    }

    /**
     * usage: to add client visitData
     * @param Request $request
     * @return array
     */
    public function AddClientVisitData(Request $request)
    {
        if (Visit::create($request->post())) {
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "添加成功";
            $this->returnData['code'] = 1;
        }
        return $this->returnData;
    }

    /**
     * add company data
     * @param Request $request
     * @return array
     */
    public function addCompany(Request $request)
    {
        try {
            Company::create($request->post());
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "添加成功";
            $this->returnData['code'] = 1;
        } catch (\Exception $e) {
            $this->returnData['msg'] = "添加失败, 请完善公司信息";
        }
        return $this->returnData;
    }

    /**
     * usage: add client
     * @param Request $request
     * @return array
     */
    public function addClient(Request $request)
    {
        $data = $request->post();
        $staff = Staff::find(Auth::guard('admin')->user()->staff_id);
        if (Department::find($staff->getOriginal('department_id'))->getOriginal('assignable')) {
            //if the staff who added the client is in the department of assignable, then the client is belongs to their own,
            //else, will be like new client without client_assign_to or client_assign_to = -1
            $data['client_assign_to'] = $staff->staff_id;
        }
        if ($this->createClient($data)) {
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "客户添加成功";
            $this->returnData['code'] = 1;
        } else {
            $this->returnData['msg'] = "客户添加失败, 请完善客户信息";
        }
        return $this->returnData;
    }

    private function createClient($data)
    {


        $data['client_added_by'] = Auth::guard('admin')->user()->staff_id;
        $data['client_new_enquiries'] = '1';

        $client = Client::where('client_mobile', '=', $data['client_mobile'])->first();
        if ($client) {
            $data['client_enquiries'] = $data['client_enquiries'] . "(" . date("Y-m-d") . ") " . PHP_EOL . $client->client_enquiries;
        }
        try {
            return Client::updateOrCreate(['client_mobile' => $data['client_mobile']], $data);
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * usage: get client data
     * @param Request $request
     * @return array
     */
    public function getClientDetail(Request $request)
    {
        //get client info by client id
        $client = Client::where('clients.client_id', '=', $request->post('client_id'))
            ->where('clients.client_status', '=', '1')->first();

        //get next visit date
        if ($visitNextDate = Visit::where('visit_client_id', '=', $client->client_id)->orderBy('visit_next_date', 'desc')->first()) {
            $client['visit_next_date'] = $visitNextDate->visit_next_date;
        }
        //change client enquiries format form \r\n to <span>tag
        $client->client_enquiries = str_replace("\r\n", '</span> <span class="badge badge-secondary">', $client->client_enquiries);
        //process the first bit
        /**
         * as teh client_enquiries format as 111111\r\n22222222\r\n
         * after str_replace,  format as 111111</span><span>22222222</span><span>
         * then add the last bit <span>at the beginning and </span> at the end , so the final piece is
         *<span>111111</span><span>22222222</span><span></span>
         */
        $client->client_enquiries = '<span class="badge badge-success">' . $client->client_enquiries . '</span>';

        $companies = Company::where('company_client_id', '=', $client->client_id)->get(); //get the company info
        $visit = Visit::where('visit_client_id', '=', $client->client_id)->orderBy('visit_next_date', 'desc')->get(); //get all the visit records

        $clientQlfs = Storage::disk('CRM')->files("client/QLF/{$client->client_id}/");
        $client['qlf'] = $clientQlfs;

        if ($client) {
            $this->returnData['status'] = true;
            $this->returnData['data'] = $client;
            $this->returnData['company'] = $companies;
            $this->returnData['visit'] = $visit;
        } else {
            $this->returnData['msg'] = '获取客户信息失败';
        }
        return $this->returnData;
    }

    /**+
     * usage: acknowledge client,
     * after acknowledge the client, the client can not be reassigned again by the infoDepartment,
     * only can be reassigned by the department-manager
     * as the system designed:
     *          client_assign_to used for indicate the client belongs to which staff
     *          client_new_enquiries: used 1: indicate there is a new enquiries from the client
     *                                     2: flag the assigned staff has been acknowledge the client or not
     * @param Request $request
     * @return array
     */
    public function acknowledgeClient(Request $request)
    {
        $client = Client::find($request->post('client_id')); //get the staff info
        if (!$client->client_assign_to) { //Pool Client
            $client->client_assign_to = Auth::guard('admin')->user()->staff_id;
            $this->returnData['data'] = 'refresh';
        } else {
            $client->client_new_enquiries = '0'; //change the client_new_enquires to 0
        }

        if ($client->save()) {
            $this->returnData['status'] = true;
            $this->returnData['msg'] = '认领成功';
            $this->returnData['code'] = 1;
        } else {
            $this->returnData['msg'] = '认领失败';
        }
        return $this->returnData;
    }

    /**
     * use to modify the client Info
     * @param Request $request
     * @return array
     */
    public function modifyClientInfo(Request $request)
    {
        $staffLevel = Staff::find(Auth::guard('admin')->user()->staff_id)->staff_level; // get staffLevel
        $data = $request->post(); //assign the post data into variable
        if($staffLevel >=3){
            if(Client::find($data['client_id'])->update($data)){
                $this->returnData['status'] = true;
                $this->returnData['msg'] = "客户信息修改成功";
                $this->returnData['code'] = 1;
            }
        }else{
            $this->requestApproval($data);
        }

        return $this->returnData;
    }

    /**
     * usage: get company info
     * @param Request $request
     * @return array
     */
    public function getCompanyInfo(Request $request)
    {
        if ($this->returnData['data'] = Company::find($request->post('company_id'))) {
            $this->returnData['status'] = true;
        } else {
            $this->returnData['msg'] = "获取公司信息失败";
        }
        return $this->returnData;
    }

    /**
     * usage: modify the company info
     * @param Request $request
     * @return array
     */
    public function modifyCompany(Request $request)
    {
        if (Company::find($request->post('company_id'))->update($request->post())) {
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "修改公司信息成功";
            $this->returnData['code'] = 1;
        } else {
            $this->returnData['msg'] = "修改公司信息失败";
        }
        return $this->returnData;
    }

    /**
     * usage: assign client into Pool
     * @param Request $request
     * @return array
     */
    public function toPool(Request $request)
    {
        $client = Client::find($request->post('client_id')); //get client data
        //rule for assign client into pool
        //1. client has been created at least 3 days ago,
        //2. the client has never made an purchase from us
        if (date("Y-m-d", strtotime("{$client->created_at}+3 day")) < date("Y-m-d") && $client->client_level <= 0) {
            $client->client_assign_to = '0'; // use this to indicate the client in the pool

            if ($client->save()) {
                $this->returnData['status'] = true;
                $this->returnData['msg'] = "成功放入公海";
                $this->returnData['code'] = 1;
            } else {
                $this->returnData['msg'] = "放入公海失败";
            }
        } else {
            $this->returnData['msg'] = "不可以放入公海";
            $this->returnData['code'] = 5;
        }
        return $this->returnData;
    }

    /**
     * Usage: this function as agent, according to the input variable value, decided to call different clientList
     * @param Request $request
     * @return array
     */
    public function getClientList(Request $request)
    {
        //get staff level
        $staffLevel = Staff::find(Auth::guard('admin')->user()->staff_id)->staff_level;
        $staffId = Auth::guard('admin')->user()->staff_id; //get staff id
        $func = $request->post('type') . "ClientList"; //structure the func string for callback

        //call back different function to get different client list
        if ($clientList = call_user_func([$this, $func], $staffId, $staffLevel)) {
            $this->returnData['status'] = true;
            $this->returnData['data'] = $clientList;
        } else {
            $this->returnData['msg'] = "获取客户类表失败";
        }
        return $this->returnData;
    }

    /**
     * usage: get pending list, as the this function need to be call somewhere else, soi has to set to public
     * @param $staffId
     * @param $staffLevel
     * @return mixed
     */
    public function pendingClientList($staffId, $staffLevel)
    {
        $clientList = array();
        switch ($staffLevel) {
            case "0": //普通员工
                $clientList = Client::where('clients.client_assign_to', '=', $staffId)
                    ->where('clients.client_status', '=', '1')
                    ->get();
                break;
            case "3": //部门经理
                $clientList = Client::where('clients.client_status', '=', '1')
                    ->where('clients.client_assign_to', '>', '0')
                    ->get();
                break;
        }
        $filteredClientList = array();
        foreach ($clientList as $key => $client) { //get client next vist date
            if ($visitNextDate = Visit::where('visit_client_id', '=', $client->client_id)
                ->where('visit_next_date', '<=', date('Y-m-d'))
                ->orderBy('visit_next_date', 'desc')->first()) {
                $filteredClientList[$key] = $client->toArray(); //convert client Obj into array
                $filteredClientList[$key]['visit_next_date'] = $visitNextDate ? $visitNextDate->visit_next_date : ''; //assign next visit date into client Array
                $filteredClientList[$key]['visit_status'] = $visitNextDate ? $visitNextDate->visit_status : ''; //assign next visit status into client Array
            }
        }
        $clients['list_name'] = '待回访客户信息';
        $clients['bg'] = 'bg-warning';
        $clients['clients'] = $filteredClientList;
        return $clients;
    }

    /**
     * usage: get pool client list
     * @param $staffId
     * @param $staffLevel
     * @return mixed
     */
    private function poolClientList($staffId, $staffLevel)
    {
        $clientList = Client::where('client_assign_to', '=', '0')
            ->where('client_status', '=', '1')
            ->orderBy('updated_at', 'desc')
            ->get();
        foreach ($clientList as $key => $client) {
            $visitNextDate = Visit::where('visit_client_id', '=', $client->client_id)->orderBy('visit_next_date', 'desc')->first();
            $clientList[$key]['visit_next_date'] = $visitNextDate ? $visitNextDate->visit_next_date : '';
            $clientList[$key]['visit_status'] = $visitNextDate ? $visitNextDate->visit_status : '';
        }
        $clients['list_name'] = '公海信息';
        $clients['bg'] = 'bg-primary';
        $clients['clients'] = $clientList;
        return $clients;
    }

    /**
     * Usage: freshly assigned/added client
     * @param $staffId
     * @param $staffLevel
     * @return mixed
     */
    private function newClientList($staffId, $staffLevel)
    {
        $clientList = '';
        switch ($staffLevel) {
            case "0": //普通员工
                $clientList = Client::where('clients.client_assign_to', '=', $staffId)
                    ->where('clients.client_status', '=', '1')
                    ->where('clients.client_new_enquiries', '=', '1')
                    ->get();
                break;
            case "3": //部门经理
                $clientList = Client::where('clients.client_status', '=', '1')
                    ->where('clients.client_new_enquiries', '=', '1')
                    ->where('clients.client_assign_to', '=', '-1')
                    ->get();
                break;
        }
        foreach ($clientList as $key => $client) {
            $visitNextDate = Visit::where('visit_client_id', '=', $client->client_id)->orderBy('visit_next_date', 'desc')->first();
            $clientList[$key]['visit_next_date'] = $visitNextDate ? $visitNextDate->visit_next_date : '';
            $clientList[$key]['visit_status'] = $visitNextDate ? $visitNextDate->visit_status : '';
        }
        $clients['list_name'] = '新客户信息';
        $clients['bg'] = 'bg-danger';
        $clients['clients'] = $clientList;
        return $clients;
    }

    private function requestApproval($data){
        if (Client::find($data['client_id'])->client_mobile == $data['client_mobile']) { //check is try to modify the client mobile data
            //not modify client mobile data
            if (Client::updateOrCreate(['client_id' =>$data['client_id']], $data)) { //update the client info
                $this->returnData['status'] = true;
                $this->returnData['msg'] = "客户信息修改成功";
                $this->returnData['code'] = 1;
            }
        } else { //update the client mobile info
            $oldClient = Client::find($data['client_id'])->toArray(); //get the client data
            $change['old'] = $oldClient; //assign the data into old change
            //
            $newInfo = array_diff_assoc($data, $oldClient); // get the difference between the new data and original data, filter out the same data
            $change['new'] = $newInfo; //assign filtered data into new change
            $changeInfo = [ //structure the info change data
                'info' => '客户敏感信息修改,请审批',
                'model' => 'App\Model\Client',
                'pk' => $data['client_id'],
                'change' => $change,
                'by' => Auth::guard('admin')->user()->name,
            ];

            //save the changeInfo into json to pre-designed folder
            Storage::disk('CRM')->put("client/change/{$data['client_id']}", json_encode($changeInfo));
            //change the client status into lock status
            if (Client::find($data['client_id'])->update(['client_status' => '2'])) {
                $this->returnData['status'] = true;
                $this->returnData['msg'] = "修改客户敏感, 客户已锁定, 请等候部门经理审批";
                $this->returnData['code'] = 5;
            }
        }
    }

    public function qualificatesUpload(Request $request){
        $clientQAFolder = Storage::disk('CRM')->directories();

//        $request->file()->getClientOriginalName()
        foreach ($request->file() as $file){
            if($file->storeAs("/client/QLF/{$request->post('client_id')}",$file->getClientOriginalName(),'CRM')){
                $this->returnData['status'] = true;
                $this->returnData['msg'] = "客户资质上传成功";
                $this->returnData['code'] = 1;
            }else{
                $this->returnData['msg'] = "客户资质上传失败";
            }
            return $this->returnData;
        }

    }


}