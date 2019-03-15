<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    private $_getInfoArray = array(); //array to store the info got from the function

    //array use to store the location of the file to function to read
    private $_fileLocation = array();

    /**
     * assign function name into the function collection,
     * when walk through the array, each function will be call to read the file
     * so when a new type of file need to read, just add the file location and fhe funciton name to read the file
     */
    public function __construct()
    {
        //file location
        $this->_fileLocation = [
            'client' => 'client/change/',
        ];
        //function to read the file
        $this->_getInfoArray = [
            'getClientInfo',
        ];
    }

    /**
     * usage: display the index page with data loaded
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * the data structure is
     *$data ['type'] =====>like client, staff, according to the info need to process, holds the data for client, staff
     *
     */
    public function index()
    {
        $data = array(); // var ot store the reading data

        foreach ($this->_getInfoArray as $getFunction) {
            //call each reading function to read the file, then assign to the data array
            $data = call_user_func([$this, $getFunction]);
        }
        return view('admin/approval/index', ['data' => $data]); //show the index page
    }

    /**
     * usage: processing the request, regarding to the decisition made:  pass:1/fail:0;
     * @param Request $request
     * $request format:
     *              infoType: table name / type of the info need to modify,
     *                      only used for allocation of the folder
     *              type: 0/1 ===>0= disapproval, 1=approval
     *              pk: the pk of the record, only used for allocation the file
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function process(Request $request)
    {
        //get the file content whis is json string
        $file = Storage::disk('CRM')->get($this->_fileLocation[$request->post('infoType')] . $request->post('id'));
        //decode the json string into array
        $file = json_decode($file, true);


        if ($request->post('type')) { //approved
            $update = $file['change']['new'];
            $update['client_status'] = '1';
        } else { //disapproved
            $update = ['client_status' => '1'];
        }

        //update the database
        if ($file['model']::find($file['pk'])->update($update)) {
            if (Storage::disk('CRM')->delete($this->_fileLocation[$request->post('infoType')] . $request->post('id'))) {
                $this->returnData['status'] = true;
                $this->returnData['msg'] = '处理成功';
                $this->returnData['code'] = 1;
            } else {
                $this->returnData['msg'] = '处理失败';
            }
        }
        return $this->returnData; //return the process result
    }

    /**
     * @return array
     * structure as below:
     * $client['client'][0] ===> ['client'] indicates the data is about client, [0] indicates this is the 0th data
     * $client['client'][0][info][model][pk][by][change]  ======>explain:
     *          info====>used for display purpose, to show to enduser what's data si about
     *          model ===>model used to operate the database table, with full namespace
     *          pk ===> primary key of this piece of data in the database
     *          by ===> who request this
     * $client['client'][0][info][model][pk][by][change][old]  ======>how the old value before any change:
     * $client['client'][0][info][model][pk][by][change][new]  ======>changed only value
     *          BOTH in the format of field=>value
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     */
    private function getClientInfo()
    {
        $clients = array();
        foreach (Storage::disk('CRM')->files('/client/change') as $clientInfo) {
            $clients['client'][] = json_decode(Storage::disk('CRM')->get($clientInfo), true);
        }

        return $clients;
    }
}
