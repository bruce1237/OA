<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;




    public function authorize(){

        $auth = json_decode(file_get_contents(storage_path('access/access.txt')),true);

        if(!$auth){
            $data['status'] = false;
            $data['msg'] = "没有授权";


            echo json_encode( $data);
            exit;
//            exit;
        }
    }


}
