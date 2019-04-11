<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    //
    public function index(){

        //get available Service();

        return view('admin/contract/index');
    }

    public function getAvailableService(){

    }
}
