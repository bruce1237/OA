<?php

namespace App\Http\Controllers\admin;

use App\Model\Func;
use App\Model\Position;
use Faker\Provider\ar_SA\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class AccessController extends Controller
{
    //
    protected $data=['status'=>false, 'msg'=>'init','icon'=>2];
    public function index(){
        //get position from database
        $positions = Position::all();

        $accessControllers =[];

        $controllers = \App\Model\Controller::where('position_id','=',0)->get();

        foreach ($controllers as $controller){
            $accessControllers[$controller['controller']] = Func::where('controller_id','=',$controller['id'])->get();
        }




      return view('admin/Access/index',['positions'=>$positions,'accessControllers'=>$accessControllers]);
    }

    public function getControllers(Request $request){
        $position_id = $request->post('position_id');


        $controllers = \App\Model\Controller::where('position_id','=',$position_id)->get();


//dd($accessControllers)
        $this->data=['status'=>true,'msg'=>$controllers];
        return $this->data;
    }

    public function addController(Request $request){
       if(\App\Model\Controller::create($request->post())){
           $this->data=['status'=>true,'msg'=>'控制器添加成功','icon'=>1];
       }
       return $this->data;
    }

    public function getFuncs(Request $request){
        $funcs = Func::where('controller_id','=',$request->post('controller_id'))->get();
        $this->data=['status'=>true,'msg'=>$funcs,'icon'=>1];
        return $this->data;
    }

    public function addFunc(Request $request){
        Func::updateOrCreate($request->post());
        $this->data=['status'=>true,'msg'=>'方法添加成功','icon'=>1];
        return $this->data;
    }

    public function addCommonControllerFuncs(Request $request){
        $controller = \App\Model\Controller::where('controller','=',$request->post('controller'))->where('position_id','=',0)->first();

        if(!$controller) {

            $controller_id = \App\Model\Controller::insertGetId(['controller' => $request->post('controller')]);
        }else{
            $controller_id = $controller->id;
        }

        Func::where('controller_id','=',$controller_id)->delete();



        $postData = $request->post();
        unset($postData['controller']);

        $func=[];
        for($i=0;$i<sizeof($postData)/2;$i++){
            if($postData['func'.$i]){
                $func[] = ['function'=> $postData['func'.$i],'controller_id' => $controller_id,'comment'=>$postData['for'.$i]];
            }

        }


       if(Func::insert($func)){
            $this->data=['status'=>true,'msg'=>'添加成功','icon'=>1];
       }

       return $this->data;
    }

    public function getAllControllers(){
        $controllerList = \App\Model\Controller::where('position_id','=',0)->get();
        $this->data=['status'=>true, 'msg'=>$controllerList,'icon'=>1];
        return $this->data;
    }

    public function getControllerFuncs(Request $request){
        $controllerName = \App\Model\Controller::find($request->post('controller_id'))->controller;

        $funcs = Func::where('controller_id','=',$request->post('controller_id'))->get();

        $this->data = ['status'=>true,'msg'=>$funcs,'icon'=>1,'controllerName'=>$controllerName];

        return $this->data;
    }
}
