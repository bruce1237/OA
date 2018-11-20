@extends('admin/layout/basic')

@section('shortcut')
    <a href="">首页</a>
    <a href="">演示</a>
    <a><cite>商标代理人</cite></a>
@endsection

@section('content')



    <div class="x-body">


        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <button class="layui-btn" onclick="new_seller_modal()"><i class="layui-icon"></i>添加
            </button>
            <span class="x-right" style="line-height:40px">共有数据：{{$sellers->total()}} 条</span>
        </xblock>
        <table class="layui-table">
            <thead>
            <tr>
                <th>
                    <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i>
                    </div>
                </th>
                <th>姓名</th>
                <th>座机</th>
                <th>手机</th>
                <th>微信</th>
                <th>地址</th>
                <th>邮编</th>

                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sellers as $key => $seller)

                <tr>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$seller->id}}'><i
                                    class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td>{{$seller->name}}</td>
                    <td>{{$seller->tel}}</td>
                    <td>{{$seller->mobile}}</td>
                    <td>{{$seller->wx}}</td>
                    <td>{{$seller->address}}</td>
                    <td>{{$seller->post_code}}</td>

                    <td class="td-manage">
                        <a title="查看" onclick="edit_seller({{$key}})" href="javascript:;">
                            <i class="icon iconfont">&#xe69e;</i>
                        </a>
                        <a title="删除" onclick="delete_seller('{{$key}}',' {{$seller->name}} ')" href="javascript:;">
                            <i class="layui-icon">&#xe640;</i>
                        </a>
                    </td>
                </tr>
            @endforeach


            </tbody>
        </table>
        <div class="page">
            <div>
                {{$sellers->links()}}
            </div>
        </div>

    </div>


    <div class="modal fade" id="sellerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="sellerModalBody">

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">姓名：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" id="seller_name"
                                   value="email@example.com" placeholder="代理的姓名">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">座机：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" id="seller_tel" value="email@example.com" placeholder="代理的座机">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">手机：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" id="seller_mobile"
                                   value="email@example.com" placeholder="代理的手机">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">微信：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" id="seller_wx" value="email@example.com"placeholder="代理的微信">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">地址：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" id="seller_address"
                                   value="email@example.com" placeholder="代理公司地址">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">邮编：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" id="seller_post_code"
                                   value="email@example.com" placeholder="代理公司邮编">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="seller_id" id="seller_id" value="test"/>
                    <button type="button" class="btn btn-danger" id="del_btn" onclick="delete_seller()">删除</button>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="save_seller()">保存修改</button>
                </div>
            </div>
        </div>
    </div>


@endsection


<script>

    sellers =<?php  echo json_encode($sellersJson['data']); ?>;

    function edit_seller(id) {
        $("#seller_id").val(sellers[id].id);
        $("#seller_name").val(sellers[id].name);
        $("#seller_tel").val(sellers[id].tel);
        $("#seller_mobile").val(sellers[id].mobile);
        $("#seller_wx").val(sellers[id].wx);
        $("#seller_address").val(sellers[id].address);
        $("#seller_post_code").val(sellers[id].post_code);


        $("#sellerModalTitle").html(sellers[id].name);
        $("#save_btn").text("修改");
        $("#del_btn").show();


        $('#sellerModal').modal('show');
    }

    function delete_seller(id,seller_name){

        var seller_id = id?sellers[id].id:$("#seller_id").val();
        var seller_name = seller_name?seller_name:'';

        layer.confirm('确认要删除'+seller_name+ '吗？',function(index){
            //发异步删除数据

        $.ajax({
            'url':'/admin/logoSeller/'+seller_id,
            'type':'delete',
            'dataType':'json',

            'data':{
                'id':seller_id,
                },
            success:function(data){
                if(data.status){
                    $('#sellerModal').modal('hide');
                    layer.msg(data.message, {icon: 1});
                    setTimeout(function(){location.replace(location.href);}, 1000);
                }else{
                    layer.msg(data.message, {icon: 1});
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                /*弹出jqXHR对象的信息*/
                alert(jqXHR.responseText);
                alert(jqXHR.status);
                alert(jqXHR.readyState);
                alert(jqXHR.statusText);
                /*弹出其他两个参数的信息*/
                alert(textStatus);
                alert(errorThrown);
            }

        });
        });
    }


    function save_seller(){
        var seller_id = $("#seller_id").val();
        var seller_name = $("#seller_name").val();
        var seller_tel = $("#seller_tel").val();
        var seller_mobile = $("#seller_mobile").val();
        var seller_wx = $("#seller_wx").val();
        var seller_address = $("#seller_address").val();
        var seller_post_code = $("#seller_post_code").val();


        $.ajax({
            'url':'/admin/logoSeller/'+seller_id,
            type:"PUT",
            'dataType':'json',
            'data':{
                'id':seller_id,
                'name':seller_name,
                'tel':seller_tel,
                'wx':seller_wx,
                'mobile':seller_mobile,
                'address':seller_address,
                'post_code':seller_post_code},
            success:function(data){
                if(data.status){
                    $('#sellerModal').modal('hide');
                    layer.msg(data.message, {icon: 1});
                    setTimeout(function(){location.replace(location.href);}, 1000);
                }else{
                    layer.msg(data.message, {icon: 1});
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                /*弹出jqXHR对象的信息*/
                alert(jqXHR.responseText);
                alert(jqXHR.status);
                alert(jqXHR.readyState);
                alert(jqXHR.statusText);
                /*弹出其他两个参数的信息*/
                alert(textStatus);
                alert(errorThrown);
            }

        });
    }


    function new_seller_modal(){
        //初始化
        $("#seller_id").val('');
        $("#seller_name").val('');
        $("#seller_tel").val('');
        $("#seller_mobile").val('');
        $("#seller_wx").val('');
        $("#seller_address").val('');
        $("#seller_post_code").val('');
        $("#sellerModalTitle").html('添加新的代理');
        $("#save_btn").text("添加");
        $("#del_btn").hide();
        $('#sellerModal').modal('show');
        $("#save_btn").attr('onclick','').click( eval(function(){new_seller()}));

    }

    function new_seller(){

        var seller_name = $("#seller_name").val();
        var seller_tel = $("#seller_tel").val();
        var seller_mobile = $("#seller_mobile").val();
        var seller_wx = $("#seller_wx").val();
        var seller_address = $("#seller_address").val();
        var seller_post_code = $("#seller_post_code").val();

        $.ajax({
            'url':'/admin/logoSeller',
            'type':'post',
            'dataType':'json',
            'data':{'name':seller_name,'tel':seller_tel,'mobile':seller_mobile,'wx':seller_wx,'address':seller_address,'post_code':seller_post_code},
            success:function(data){
                if(data.status){
                    // $('#sellerModal').modal('hide');
                    layer.msg(data.message, {icon: 1});
                    // setTimeout(function(){location.replace(location.href);}, 1000);
                // }else{
                    layer.msg(data.message, {icon: 1});
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                /*弹出jqXHR对象的信息*/
                alert(jqXHR.responseText);
                alert(jqXHR.status);
                alert(jqXHR.readyState);
                alert(jqXHR.statusText);
                /*弹出其他两个参数的信息*/
                alert(textStatus);
                alert(errorThrown);
            }




        });



    }

    function delAll() {
        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？',function(index){
            //捉到所有被选中的，发异步进行删除

            $.ajax({
                'url':'/admin/logoSeller/'+data,
                'type':'DELETE',
                'dataType':'json',
                'data':{'id':data},
                success:function(data){
                    alert(data.status);
                    if(data.status){
                        layer.msg(data.message, {icon: 1});
                        setTimeout(function(){location.replace(location.href);}, 1000);
                    }else{
                        layer.msg(data.message, {icon: 1});
                    }



                },
                error: function (jqXHR, textStatus, errorThrown) {
                    /*弹出jqXHR对象的信息*/
                    alert(jqXHR.responseText);
                    alert(jqXHR.status);
                    alert(jqXHR.readyState);
                    alert(jqXHR.statusText);
                    /*弹出其他两个参数的信息*/
                    alert(textStatus);
                    alert(errorThrown);
                }
            });






        });

    }


</script>