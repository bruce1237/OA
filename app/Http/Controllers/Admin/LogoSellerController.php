<?php

namespace App\Http\Controllers\Admin;

use App\Model\Logo;
use App\Model\LogoSeller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;

class LogoSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $sellers = LogoSeller::paginate(8);



        $sellersJson = json_decode($sellers->toJson(),true);


        return view('admin/logoseller/logoseller',['sellers'=>$sellers,'sellersJson'=>$sellersJson]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=[];
        $seller = LogoSeller::firstOrCreate($request->post());



        if($seller->wasRecentlyCreated){
            $data['status']=true;
            $data['message'] ="新代理添加成功";
        }else{
            $data['status']=false;
            $data['message'] ="代理重复，请确认名字的唯一性";
        }

        return json_encode($data);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $seller = LogoSeller::find($request->post('id'));
        $seller->name = $request->post('name');
        $seller->mobile = $request->post('mobile');
        $seller->tel = $request->post('tel');
        $seller->wx = $request->post('wx');
        $seller->address = $request->post('address');
        $seller->post_code = $request->post('post_code');
        if($seller->save()){
            $data['status']=true;
            $data['message'] = "修改成功";

        }else{
            $data['status']=false;
            $data['message'] = "修改失败";
        }
        return json_encode($data);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = explode(',',$id);
//        dd(Logo::whereIn('seller_id',$id)->doesntExist());
        $data=[];
        if(Logo::whereIn('seller_id',$id)->doesntExist()){
            $result = LogoSeller::destroy($id);
            if($result){
                $data['status']=true;
                $data['message'] = "删除成功";

            }else{
                $data['status']=false;
                $data['message'] = "删除失败";
            }
        }else{

            $data['status']=false;
            $data['message'] = "请先删除此卖家下面的商标，然后在删除卖家！";
//            dd($data);
        }





        return json_encode($data);





    }
}
