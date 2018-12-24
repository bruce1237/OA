<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function tt(){
        $a = "AAA";
        dd(get_class_methods('App\Http\Controllers\Admin\HRController'));
    }

}

