<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoDepartController extends Controller
{
    //show the index page of the info setting page
    public function index()
    {
        $infoSource = $this->getData('InfoSource'); //get all the data from the database for the table:Info_Source
        $visitStatus = $this->getData('visitStatus'); //get all the data from the database for the table Visit_Status
        $orderType = $this->getData('orderType'); //get all the data from the database for the table order_type
        $paymentMethod = $this->getData('paymentMethod'); //get all the data from the database for the table payment_method
        $orderStatus = $this->getData('orderStatus'); //get all the data from the database for the table order_status
        $services = $this->getData('service'); //get all the data from the database for the table service
        $firms = $this->getData('firm'); // get all the data from the database for the table firm
        $departments = $this->getData('department'); // get all the data from the database for the table department

        $serviceByCate = array(); //create an array for serviceCategory to reorganize service category order
        foreach ($services as $key => $service) { //reorganize the structure of the service category array data
            if ($service['service_parent_id'] == 0) {
                $serviceByCate[] = $service;
                foreach ($services as $subService) {
                    if ($service['service_id'] == $subService['service_parent_id']) {
                        $serviceByCate[] = $subService;
                    }
                }
            }
        }

        // create an array to pass the data into blade
        $data = [
            'infoSource' => $infoSource,
            'visitStatus' => $visitStatus,
            'orderType' => $orderType,
            'paymentMethod' => $paymentMethod,
            'orderStatus' => $orderStatus,
            'service' => $serviceByCate,
            'firms' => $firms,
            'departments' => $departments,
        ];
        return view('admin/InfoDepart/index', ['data' => $data]);
    }

    /**
     * @param $table table in the Model
     * @return mixed all the data in assoc array structure of the table
     */
    public function getData($table)
    {
        return ($this->modalFullNameSpace . $table)::all();
    }

    /**
     * @param Request $request //request obj for the data passed from blade
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector data for the ajax method
     */
    public function infoUpdate(Request $request)
    {

        //as the firm section involves pictures and data, it is hard to combine with ajax method, so use form post to process CURD firm request
        if (!$request->ajax()) { //for the firm CURD

            //assign request post data into variable
            $data = $request->post();
            //unset the unnecessary data
            unset($data['type']); //this indicates the C,U,R,D
            unset($data['_token']); // this is used for csrf section

            switch ($request->post('type')) {
                case "create": //new firm
                    //insert the post data and assign the returned  insert id into variable
                    $insertId = Firm::create($data)->firm_id;
                    if ($insertId && $request->file('seal')) {//if they upload an contract seal nad the data insertion success, then deal with contract seal png image
                        //save the uploaded contract seal
                        if ($request->file('seal')->storeAs("firm/{$insertId}/seal/", $insertId . ".png", "CRM")) { //save seal success
                            //modify the return data
                            $this->returnData['status'] = true;
                            $this->returnData['msg'] = "添加成功";
                            $this->returnData['code'] = 1;
                        } else {
                            $this->returnData['msg'] = "添加成功, 但是公章上传失败";
                        }
                    } else {
                        $this->returnData['msg'] = "添加失败";
                    }
                    break;
                case "update"://update firm
                    $firm_id = $request->post('firm_id'); //put the posted firm id into variable
                    unset($data['firm_id']); // unset the unnecessary data
                    Firm::find($request->post('firm_id'))->update($data); // update the firm info
                    if ($request->file('seal')) { //if upload a new contract seal,
                        Storage::disk('CRM')->delete("firm/{$firm_id}/seal/{$firm_id}.png"); //remove the old seal
                        $request->file('seal')->storeAs("firm/{$firm_id}/seal/", $firm_id . ".png", 'CRM'); //upload the new seal
                    }
                    break;
                case "delete": //deltet firm

                    $firm_id = $request->post('firm_id'); //put the posted firm id into variable
                    Firm::destroy($request->post('firm_id')); //delete the deleted firm
                    Storage::disk('CRM')->delete("firm/{$firm_id}/seal/{$firm_id}.png"); // remove/unlink/delete  the seal
                    break;
            }

//            return view('admin/InfoDepart/index', ['data' => $this->returnData]);
            return redirect('admin/crm_info'); //back the info department index page


        } elseif ($request->ajax()) { //use ajax method to process all the data except the firm

            $type = $request->post('type');//acquire the action types for this operation: create/update/delete/get
            $table = str_replace("_", '', $request->post('tableName'));//change the tableName to Modal
            $id = $request->post('id'); //operation on the ID/PK
            $data = json_decode($request->post('data'), true); //analyze the data

            if (!is_array($data)) {
                // if the data is not a array,
                // then, it is a serialized string,it need to be converted to an array
                $str = explode('&', $data);
                $tempVar = array(); //create a temporary variable to store the request data
                foreach ($str as $frag) {
                    $arr = explode('=', $frag);
                    $tempVar[$arr[0]] = $arr[1];
                }
                $data = $tempVar;
            }
            $model = $this->modalFullNameSpace . $table; // get the table model
            if ($table == 'paymentmethod') { //process the payment method data
                $attributes = array();
                for ($i = 1; $i < 6; $i++) {
                    if ($data['key_' . $i]) {
                        $attributes[$data['key_' . $i]] = $data['value_' . $i];
                    }
                    unset($data['key_' . $i]);
                    unset($data['value_' . $i]);
                }
                $data['payment_method_attributes'] = $attributes;
            }

            if ($this->tableModify($type, $model, $id, $data)) { //modify the table
                //modify success
                $this->returnData['status'] = true;
                $this->returnData['code'] = 1;
                $this->returnData['msg'] = "操作成功!";
            } else {
                //modify failed
                $this->returnData['code'] = 2;
                $this->returnData['msg'] = "操作失败!";
            }
            return $this->returnData;
        }
    }

    /**
     * @param $type use to indicate how to modify the table: CURD
     * @param $model table model
     * @param $id the PK value
     * @param array $data [$filed=>$value] for the table
     * @return bool whether the process succeed or not
     */
    private function tableModify($type, $model, $id, array $data)
    {
        switch ($type) {
            case "create":
                return $model::create($data);
                break;
            case "update":

                return $model::find($id)->update($data);
                break;
            case "delete":
                return $model::destroy($id);
                break;
            default:
                return false;
                break;
        }
    }
}
