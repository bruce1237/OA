<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * usage: show the contract template page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $templates = Template::all(); //get the template data from database
        $data = [
            'templates' => $templates,
        ];
        return view('admin/template/index', ['data' => $data]); //show the index page
    }

    /**
     * usage: modify the contract template table
     * @param Request $request
     * @return array
     */
    public function templateModify(Request $request)
    {
        //get the template table model
        $model = $this->modalFullNameSpace . $request->post('table');
        switch ($request->post('type')) { //get the CURD type
            case "create": //create contract name related with service table
                if ($model::create([$request->post('field') => $request->post('data')])) {
                    $this->returnData['status'] = true;
                    $this->returnData['msg'] = "添加成功";
                    $this->returnData['code'] = 1;
                } else {
                    $this->returnData['msg'] = "添加失败";
                }
                break;
            case "update":
                //restructure the post data into a key value array
                $data = [$request->post('field') => $request->post('data')];
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
}
