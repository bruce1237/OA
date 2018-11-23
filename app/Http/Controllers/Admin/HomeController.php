<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
    public function showDashboard(){
        $a = "navbar";
        return view('admin/home/dashboard',['a'=>$a]);
    }
}
