<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Service;
use App\Model\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller {
    /**
     * usage: show the contract template page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $templates = Template::all(); //get the template data from database


        //organize Services structure
        $structuredServices = $this->getServices();




        $data = [
            'services' => $structuredServices,
            'templates' => $templates,
        ];
        return view('admin/template/index', ['data' => $data]); //show the index page
    }

    /**
     * usage: modify the contract template table
     * @param Request $request
     * @return array
     */
    public function templateModify(Request $request) {
//        dd($request->post());
        //get the template table model
        $model = $this->modalFullNameSpace . $request->post('table');
        $data['template_id'] = $request->post('id');
        switch ($request->post('type')) { //get the CURD type
            case "order":


                try {
                    $request->file('file')->storeAs('Order/Temp/', $request->post('id') . ".php", 'CRM');
                    $this->returnData['status'] = true;
                    $this->returnData['msg'] = "添加成功";
                    $this->returnData['code'] = 1;
                } catch (\Exception $e) {
                    $this->returnData['msg'] = "添加失败";
                }


                break;

            case "create": //create contract name related with service table

                $data[$request->post('field')] = $request->post('data');
                try {
                    copy(public_path('\storage\CRM\Order\Temp\default.php'),public_path("\storage\CRM\Order\Temp\\{$request->post('id')}.php"));
                    $template_id = $model::create($data);
                    dd($template_id);
                    $this->returnData['status'] = true;
                    $this->returnData['msg'] = "添加成功";
                    $this->returnData['code'] = 1;

                } catch (\Exception $e) {
                    dd($e->getMessage());
                    $this->returnData['msg'] = "添加失败";
                }
                break;
            case "update":
                //restructure the post data into a key value array

                if ($request->file('file')) { //process the upload template file
                    //remove the old template file
                    Storage::disk('contract')->delete($model::find($request->post('id'))->template_file);
                    //generate a new name for the uploaded template file
                    $template_name = uniqid() . "." . $request->file('file')->getClientOriginalExtension();
                    //save the uploaded file
                    $template_file = $request->file('file')->storeAs('', $template_name, 'contract');
                    if ($template_file) { //save success
                        $data['template_file'] = $template_file;
                    }
                }
                //update the template table data

                if ($model::find($request->post('id'))->update($data)) {
                    $this->returnData['status'] = true;
                    $this->returnData['msg'] = "模板上传成功";
                    $this->returnData['code'] = 1;
                } else {
                    $this->returnData['msg'] = "模板上传失败";
                }
                break;
            case "delete": //remove the template
                Storage::disk('contract')->delete($model::find($request->post('id'))->template_file); //remove the contract file
                if ($model::destroy($request->post('id'))) { //remove the template data in the template table
                    $this->returnData['status'] = true;
                    $this->returnData['msg'] = "删除成功";
                    $this->returnData['code'] = 1;
                } else {
                    $this->returnData['msg'] = "删除失败";
                }
                break;
        }
        return $this->returnData;
    }

    public function getServices(){
        $structuredServices = array();
        $services = Service::all();

        foreach ($services as $key=>$service) {
            if (!$service->service_parent_id) {
                $structuredServices[$key][$service->service_name] = "disabled";
                foreach ($services as $s) {
                    if ($s->service_parent_id == $service->service_id) {
                        $structuredServices[$key][$s->service_name] = $s->service_id;
                        $structuredServices[$key][$s->service_name.'cost'] = $s->service_cost;
                    }
                }
            }
        }

        return $structuredServices;
    }
}
