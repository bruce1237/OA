<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function tt(){
        $a = "AAA";
        return view('admin/test/index',['a'=>$a]);
    }

}

