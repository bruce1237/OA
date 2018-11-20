@extends('admin/layout/pop')

@section('content')

    <label>昵称: {{$admin['name']}}</label>
    <label>邮箱: {{$admin['email']}}</label>
    <hr/>


    <div class="layui-form-item">
        <label for="L_pass" class="layui-form-label">
            <span class="x-red">*</span>旧密码
        </label>
        <div class="layui-input-inline">
            <input type="password" id="old_pass" name="pass" required="" lay-verify="pass"
                   autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">
            6到16个字符
        </div>
    </div>

    <div class="layui-form-item">
        <label for="L_pass" class="layui-form-label">
            <span class="x-red">*</span>新密码
        </label>
        <div class="layui-input-inline">
            <input type="password" id="L_pass" name="pass" required="" lay-verify="pass"
                   autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">
            6到16个字符
        </div>
    </div>
    <div class="layui-form-item">
        <label for="L_repass" class="layui-form-label">
            <span class="x-red">*</span>确认密码
        </label>
        <div class="layui-input-inline">
            <input type="password" id="L_repass" name="repass" required="" lay-verify="repass"
                   autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">
            6到16个字符
        </div>
    </div>
    <div class="layui-form-item">
        <label for="L_repass" class="layui-form-label">
        </label>
        <button class="layui-btn" lay-filter="add" lay-submit="" onclick="changePwd()">修改密码</button>
    </div>


    <script>

       function changePwd(){

           var oldPwd=$("#old_pass").val();
           var newPwd=$("#L_pass").val();
           var conPwd=$("#L_repass").val();

           $.ajax({

               'url':"/admin/changePwd",
               'type':"post",
               'dataType':"json",
               'data':{"oldPwd":oldPwd,"newPwd":newPwd,"conPwd":conPwd},
               success:function(data){
                   alert(data);
               }

           });
       }






    </script>



    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
            var form = layui.form
                ,layer = layui.layer;

            //自定义验证规则
            form.verify({
                nikename: function(value){
                    if(value.length < 5){
                        return '昵称至少得5个字符啊';
                    }
                }
                ,pass: [/(.+){6,12}$/, '密码必须6到12位']
                ,repass: function(value){
                    if($('#L_pass').val()!=$('#L_repass').val()){
                        return '两次密码不一致';
                    }
                }
            });

            //监听提交
            form.on('submit(add)', function(data){
                console.log(data);
                //发异步，把数据提交给php
                layer.alert("增加成功", {icon: 6},function () {
                    // 获得frame索引
                    var index = parent.layer.getFrameIndex(window.name);
                    //关闭当前frame
                    parent.layer.close(index);
                });
                return false;
            });


        });
    </script>



@endsection