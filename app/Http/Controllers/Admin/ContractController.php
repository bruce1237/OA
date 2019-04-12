<?php

namespace App\Http\Controllers\Admin;

use App\Model\Contract;
use App\Model\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    //
    public function index(){

        //get available Service();
        $usedServices = $this->getAvailableService();
        $unUsedService = $this->getUnusedService($usedServices);

        $contracts = Contract::all();

        $data=[
            'freshServices' =>$unUsedService,
            'contracts' =>$contracts,
        ];






        return view('admin/contract/index',['data'=>$data]);
    }

    public function showContractServices(Request $request){
        $serviceIds = json_decode($request->post('services'),true);
        try{
            $services = Service::whereIn('service_id',$serviceIds)->select('service_name')->get();
            $this->returnData['status'] = true;
            $this->returnData['data'] = $services;
            $this->returnData['code'] =1;
        }catch (\Exception $e){
            $this->returnData['msg'] = $e->getMessage();
        }
        return $this->returnData;


    }

    public function uploadContract(Request $request){
        $data = $request->post();
        try{
            $data['contract_file']=uniqid().".".$request->file('file')->getClientOriginalExtension();
                $request->file('file')->storeAS('',$data['contract_file'],'contract');
        }catch (\Exception $e){
            Storage::disk('contract')->delete($data['contract_file']);
            $this->returnData['msg'] = $e->getMessage();
        }

        try{
            $old = Contract::where('contract_name','=',$data['contract_name'])->first();

           if($old){
               Storage::disk('contract')->delete($old->contract_file);
           }


            $data['contract_services'] = implode(",",json_decode($data['contract_services'],true));
            Contract::updateOrCreate(['contract_name'=>$data['contract_name']],$data);
            $this->returnData['status'] = true;
            $this->returnData['msg']='合同上传成功';
            $this->returnData['code']=1;

        }catch (\Exception $e){
            Storage::disk('contract')->delete($data['contract_file']);
            $this->returnData['msg'] .= $e->getMessage();
        }
        return $this->returnData;

    }

    public function getUnusedService(array $usedServices){
        return $unusedServices = Service::whereNotIn('service_id',$usedServices)
            ->where('service_parent_id','!=','0')
            ->orderBy('service_parent_id')->get();
    }

    public function getAvailableService():array {
        $usedService = Contract::selectRaw("group_concat(contract_services) as usedService")
            ->first();

        $usedService = explode(',',$usedService->usedService);

        return array_unique($usedService);
    }
}
