<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Model\Client;
use App\Model\Department;
use App\Model\Firm;
use App\Model\InfoSource;
use App\Model\InfoStatic;
use App\Model\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfoAssignController extends Controller
{
    /**
     * show the info assignment page with all the necessary value
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $today = date("Y-m-d"); //get today date for the info assign statistic usage
        $currentMonth = date("Y-m") . "%"; // get the month for the assign statistic usage

        $client_source = InfoSource::all(); //get infoSource data
        $departs = Department::where('assignable', '=', '1')->select('id')->get(); //get assignable department data
        $departIds = array(); //create variable to store the departs id
        foreach ($departs as $depart) {
            array_push($departIds, $depart->id);
        }
        //get the staffs data from the staff table which are info assignable
        $staffs = Staff::whereIn('department_id', $departIds)->orderBy('department_id')->orderBy('staff_id')->get();

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

        //restructure the staff data to meet info statistic requirements
        foreach ($staffs as $key => $staff) {
            foreach ($staff_info_static as $static) {
                if ($staff['staff_id'] == $static->client_assign_to) {
                    $staffs[$key]['today'] = $static->today_count;
                    $staffs[$key]['month'] = $static->month_count;
                }
            }
        }
        $firms = Firm::all(); //get all the firm data
        //data of clients who are not been assigned, in other word is new client or exist client with new request
        $pendingClients = Client::where('client_assign_to', '=', '-1')
            ->where('client_new_enquiries', '=', '1')
            ->orderBy('updated_at', 'desc')
            ->get();
        //restructure the pending client data, to put the first line of enquiries into the pendingClient data
        foreach ($pendingClients as $key => $pendingClient) {
            $pendingClients[$key]->client_enquiries = explode("\r\n", $pendingClient->client_enquiries)[0];
        }
        //data of clients who has been assigned but not been acknowledged by the relative staff
        $freshlyAssignClients = Client::where('client_new_enquiries', '1')->where('client_assign_to', '>', 0)->orderBy('updated_at', 'desc')->get();
        //restructure the freshly assigned client data, to put the first line of enquiries into the pendingClient data
        foreach ($freshlyAssignClients as $key => $freshlyAssignClient) {
            $freshlyAssignClients[$key]->client_enquiries = explode("\r\n", $freshlyAssignClient->client_enquiries)[0];
        }

        $data = [
            'client_sources' => $client_source,
            'staffs' => $staffs,
            'pendingClients' => $pendingClients,
            'staff_info_static' => $staff_info_static,
            'firms' => $firms,
            'freshlyAssignClients' => $freshlyAssignClients,
        ];
        return view('admin/infoAssign/index', ['data' => $data]);
    }

    /**
     * usage: assign client to staff
     * @param Request $request
     * @return array
     */
    public function assignInfo(Request $request)
    {
        $client_id = (int)$request->post('client_id'); //get client id from the post data
        $client = Client::find($client_id); //get ID client's data
        if (!$client->client_new_enquiries) { //check if the client is new client or client has new enquires and NOT been assigned staff acknowledged
            $this->returnData['msg'] = "客户已被认领,重新分配失败";
            return $this->returnData;
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
    public function infoStatic(Request $request)
    {
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
}
