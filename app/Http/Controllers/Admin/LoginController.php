<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //

    public function loginForm()
    {

        if(Auth::guard('admin')->check()){
            return redirect('admin/home');
        }
        return view('admin/login/loginForm');
    }


    public function login(Request $request)
    {
        if (Auth::guard('admin')->attempt(
            ['staff_no' => $request->post('staff_no'), 'password' => $request->post('password')],
                $request->has('remember')
            )) {
            return redirect('/admin/home');
        } else {
            return redirect('/login')->withErrors('登录信息不正确','loginError');
        }

    }

    public function info(){
        $admin['name']  = Auth::guard('admin')->user()->name;
        $admin['email'] = Auth::guard('admin')->user()->email;
        return view('admin/login/adminInfo',["admin"=>$admin]);
    }

    public function changePwd(Request $request){

        if(!$request->ajax()){
            return json_encode("请求不合法");
        }

        if(!Hash::check($request->post('oldPwd'),Auth::guard('admin')->user()->password)){
            return json_encode("旧密码不正确");
        }

        if($request->post('newPwd')!=$request->post('conPwd')){
            return json_encode("两次密码输入不一致");
        }

         if(Admin::where('id','=',Auth::guard('admin')->user()->id)->update(['password'=>bcrypt($request->post('newPwd'))])){
            return json_encode("更新成功");
         }else{
            return json_encode("未知原因，更新失败，请稍后再试");
         }





    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('');
    }




}
