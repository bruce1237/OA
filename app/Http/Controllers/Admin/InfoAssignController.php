<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Lib\clientImport\csvProcessor;
use App\Model\Client;
use App\Model\Department;
use App\Model\Firm;
use App\Model\InfoSource;
use App\Model\InfoStatic;
use App\Model\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InfoAssignController extends Controller {
    /**
     * show the info assignment page with all the necessary value
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
//$this->runTimer("StartMEM".memory_get_usage());
        $today = date("Y-m-d"); //get today date for the info assign statistic usage
        $currentMonth = date("Y-m") . "%"; // get the month for the assign statistic usage
//$this->runTimer('before get InfoSource');
        $client_source = InfoSource::all(); //get infoSource data
//$this->runTimer('after get infosource');
        $staffAssignedInfoCount = array();


        $departs = Department::where('assignable', '=', '1')->select('id')->get(); //get assignable department data
//$this->runTimer('after get department');
        $departIds = array(); //create variable to store the departs id
        foreach ($departs as $depart) {
            array_push($departIds, $depart->id);
        }
//$this->runTimer('after departs as depart foreach');
        //get the staffs data from the staff table which are info assignable
        $staffs = Staff::where('staff_status','=','1')->whereIn('department_id', $departIds)->orderBy('department_id')->orderBy('staff_id')->get();
//$this->runTimer('after get staff');
        //get the staff info assign statistic data from the database
        //there are two different ways to do so
        //$staff_info_static = DB::select("SELECT staff_id, count(assign_date=? or null) as today_count,
        //count(assign_date like ? or null) as month_count from info_statics group by staff_id", [$today, $currentMonth]);
        $staff_info_static = DB::table('clients')->selectRaw('client_assign_to, 
            count(client_assign_date=? or null) as today_count,
            count(client_assign_date like ? or null) as month_count',
            [$today, $currentMonth])
            ->where('client_assign_to', '!=', '-1')
            ->groupBy('client_assign_to')
            ->get();


//$this->runTimer('after get staff_info_static');
        //restructure the staff data to meet info statistic requirements
        foreach ($staffs as $key => $staff) {
            foreach ($staff_info_static as $static) {
                if ($staff['staff_id'] == $static->client_assign_to) {
                    $staffs[$key]['today'] = $static->today_count;
                    $staffs[$key]['month'] = $static->month_count;
                    $staffAssignedInfoCount[$staff->staff_id] = ['today' => $static->today_count,
                        'month' => $static->month_count];
                }
            }
        }


//$this->runTimer('after staffs as staff foreach');
        $firms = Firm::all(); //get all the firm data
//$this->runTimer('after get Firms');
        //data of clients who are not been assigned, in other word is new client or exist client with new request
        $pendingClients = Client::where('client_assign_to', '=', '-1')
            ->where('client_new_enquiries', '=', '1')
            ->orderBy('updated_at', 'desc')
            ->get();
//$this->runTimer('after get pending Clients');
        //restructure the pending client data, to put the first line of enquiries into the pendingClient data
        foreach ($pendingClients as $key => $pendingClient) {
            $pendingClients[$key]->client_enquiries = explode("\r\n", $pendingClient->client_enquiries)[0];
        }
//$this->runTimer('after pendingClients as pendingclient foreach');
        //data of clients who has been assigned but not been acknowledged by the relative staff
        // $freshlyAssignClients = Client::where('client_new_enquiries', '=', '1')->where('client_assign_to', '>', 0)
        //     ->where('Staff.staff_status','=',"1")
        //     ->join('Staff', 'clients.client_assign_to', '=', 'Staff.staff_id')
        //     ->select('clients.*', 'Staff.staff_name')
        //     ->orderBy('clients.updated_at', 'desc')->get();

        // //restructure the freshly assigned client data, to put the first line of enquiries into the pendingClient data
 
        // foreach ($freshlyAssignClients as $key => $freshlyAssignClient) {
           
        //    $freshlyAssignClients[$key]->client_enquiries = explode("\r\n", $freshlyAssignClient->client_enquiries)[0];
        //    $freshlyAssignClients[$key]->today =key_exists($freshlyAssignClient->getOriginal('client_assign_to'),$staffAssignedInfoCount)?$staffAssignedInfoCount[$freshlyAssignClient->getOriginal('client_assign_to')]['today']:0;
        //    $freshlyAssignClients[$key]->month =key_exists($freshlyAssignClient->getOriginal('client_assign_to'),$staffAssignedInfoCount)?$staffAssignedInfoCount[$freshlyAssignClient->getOriginal('client_assign_to')]['month']:0;
          
        // }

       $freshlyAssignClients=array();

//$this->runTimer('after freshlyAssignClients foreach');

        $staffSelectOption = '';
        foreach ($staffs as $staff) {
            $staffSelectOption .= "<option value={$staff->staff_id}>{$staff->staff_name}({$staff->department_id}) {$staff->today}/ {$staff->month}</option>";
        }


        $data = [
            'client_sources' => $client_source,
            'staffs' => $staffs,
            'staffSelectOption' => $staffSelectOption,
            'pendingClients' => $pendingClients,
            'staff_info_static' => $staff_info_static,
            'firms' => $firms,
            'freshlyAssignClients' => $freshlyAssignClients,
        ];
//$this->runTimer("StartMEM".memory_get_usage());
        return view('admin/infoAssign/index', ['data' => $data]);
    }

    /**
     * usage: assign client to staff
     * @param Request $request
     * @return array
     */
    public function assignInfo(Request $request) {


        $client_id = (int)$request->post('client_id'); //get client id from the post data
        $client = Client::find($client_id); //get ID client's data
//        $staffId=Auth::guard('admin')->user()->staff_id;
//        $staffLevel = Staff::find($staffId)->staff_level;


        if (null == ($request->post('overwrite')) || !$request->post('overwrite')) {
            if ($client->client_new_enquiries == '0') { //check if the client is new client or client has new enquires and NOT been assigned staff acknowledged

                $this->returnData['msg'] = "客户已被认领,重新分配失败";
                return $this->returnData;
            }
        }

        if ($client->getOriginal('client_assign_to') >= 0) { //reassign client if the client has been assigned, delete the old assign statistic data
            InfoStatic::where('staff_id', '=', $client->getOriginal('client_assign_to'))->where('client_id', '=', $client_id)->delete();
        }
        //create new record for the client assign activity
        InfoStatic::create(['staff_id' => $request->post('staff_id'),
            'client_id' => $request->post('client_id'),
            'info_source' => $client->client_source,
            'assigned_at' => date("Y-m-d")]);
        //update client info for the client assignment
//        dd($request->post('staff_id'));
        if (Client::find($request->post('client_id'))->update(['client_assign_to' => $request->post('staff_id'), 'client_assign_date' => date('Y-m-d')])) {
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "分配成功";
            $this->returnData['code'] = 1;
        } else {
            $this->returnData['msg'] = "分配失败";
        }
        return $this->returnData;
    }

    /**
     * usage: statistic staff-info(client) activity
     * @param Request $request
     * @return array
     */
    public function infoStatic(Request $request) {
        //get statistic data from database
        $static_result = DB::table('info_statics')
            ->selectRaw('info_source, count(*) as count')
            ->where('assigned_at', '>=', $request->post('startDate'))
            ->where('assigned_at', '<=', $request->post('startDate'))
            ->where('staff_id', '=', $request->post('staff_id'))
            ->groupBy('info_source')
            ->get();
        //analysis the statistic result
        if (sizeof($static_result)) {
            $this->returnData['status'] = true;
            $this->returnData['data'] = $static_result;
        } else {
            $this->returnData['msg'] = "没有分配信息";
        }
        return $this->returnData;
    }

    public function uploadClientInfoFile(Request $request) {
        $uploadFile = $request->file('file');
        $newFileName = uniqid() . "." . $uploadFile->getClientOriginalExtension();
        if (!$uploadFile->storeAs('Temp/', $newFileName, 'CRM')) {
            $this->returnData['msg'] = "文件上传错误, 请重试";
        }
        $fileRes = public_path('storage\crm\temp\\') . $newFileName;

        if (csvProcessor::process($fileRes, $request->post('sourceId'), $request->post('firmId'))) {
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "导入成功";
            $this->returnData['code'] = 1;
        } else {
            $this->returnData['msg'] = "处理上传文件失败";
        }

        if (!Storage::disk('CRM')->delete("Temp/{$newFileName}")) {
            $this->returnData['msg'] .= "移除上传文件错误, 请重试";
        }

        return $this->returnData;
    }


}
