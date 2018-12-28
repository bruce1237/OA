@extends('admin/layout/basic')

@section('shortcut')
    <button class="layui-btn layui-btn layui-btn-xs" onclick="showAddControllerFuncsModal()"><i
                class="layui-icon"></i>添加控制器和方法
    </button>
    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="showModifyControllerFuncsModal()"><i
                class="layui-icon"></i>修改控制器和方法
    </button>

@endsection

@section('content')

    <div class="row">
        <div class="col-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">选择职位</label>
                </div>
                <select class="custom-select" id="positionSelectList" onchange="positionChanged()">
                    <option selected disabled>请选择职位</option>
                    @if($positions)
                        @foreach($positions as $position)
                            <option value="{{$position->id}}">{{$position->position_name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 10px;"></div>
    <div class="row">
        <div class="col-4">
            <div id="controllers">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">添加控制器</span>
                    </div>
                    <input type="text" id="controller" class="form-control" placeholder="控制器" aria-label="Username"
                           aria-describedby="basic-addon1">

                    <div class="input-group-prepend">
                        <button class="btn btn-success" onclick="addController()">添加</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-4">
            <div id="functions">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">添加方法</span>
                    </div>
                    <input type="text" id="functionName" class="form-control" placeholder="方法名" aria-label="Username"
                           aria-describedby="basic-addon1">

                    <div class="input-group-prepend">
                        <button class="btn btn-success" onclick="addFunction()">添加</button>
                    </div>

                </div>
            </div>
        </div>

    </div>



    <div class="row">
        <div class="col-4">
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-success">
                    请添加控制器
                </div>
                <ul class="list-group list-group-flush bg-secondary" id="controllerList">

                </ul>
            </div>


        </div>
        <div class="col-4">
            <div class="card border-primary mb-3">
                <div class="card-header bg-transparent border-primary">
                    请添加方法 <span id="selectedControllerName"></span>
                </div>
                <ul class="list-group list-group-flush bg-secondary" id="funcsList">

                </ul>
            </div>

        </div>
        <div class="col-4" style="width:auto;height:650px;overflow-x:auto;overflow-y:auto">
            <div class="card border-danger mb-3" id="accessList">
                <div class="card-header bg-transparent border-danger" id="accessTitle">
                    现有的控制器和方法
                </div>
                <div class="bd-example">

                    <ul>
                        @foreach($accessControllers as $controller => $funcs)
                            <li class="list-group-item list-group-item-primary">{{$controller}}</li>
                                @foreach($funcs as $func)
                                    <li class="list-group-item">{{$func->function}} ({{$func->comment}})</li>
                                @endforeach
                        @endforeach

                    </ul>
                </div>

                <div class="card-footer bg-transparent border-danger">



                </div>

            </div>
        </div>

    </div>



    <input type="hidden" id="currentControllerID"/>



    <div class="modal fade" id="newControllerFuncs" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submenuName">添加控制器和方法</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <form id="controllerFuncsForm">
                        <div class="col-12">
                            <div class="afterProcess"></div>
                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="controller" >控制器:</span>
                                </div>
                                <input type="text" class="form-control" id="controllerName" name ="controller"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <hr />
                                @for($i=0;$i<20;$i++)


                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">方法:</span>
                                </div>
                                <input type="text" class="form-control" id="funcs{{$i}}" name="func{{$i}}"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">作用:</span>
                                </div>
                                <input type="text" class="form-control" id="fors{{$i}}" name="for{{$i}}"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                                    @endfor
                        </div>
                        </form>
                    </div>


                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="addControllerFunc()">添加</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modifyControllerFuncs" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submenuName">添加控制器和方法</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <form id="ModifycontrollerFuncsForm">
                        <div class="col-12">
                            <div class="afterProcess"></div>
                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="controller" >控制器:</span>
                                </div>
                                <input type="hidden" id="modifyModalController" name="controller" />
                                <select class="form-control" id="controllerSelection" onchange="getSelectedControllerFuncs()">

                                </select>
                            </div>

                            <hr />
                                @for($i=0;$i<20;$i++)


                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">方法:</span>
                                </div>
                                <input type="text" class="form-control" id="func{{$i}}" name="func{{$i}}"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">作用:</span>
                                </div>
                                <input type="text" class="form-control" id="for{{$i}}" name="for{{$i}}"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                                    @endfor
                        </div>
                        </form>
                    </div>


                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="modifyControllerFuncsBtn" onclick="addControllerFunc('ModifycontrollerFuncsForm')">添加</button>
                </div>
            </div>
        </div>
    </div>



@endsection

<script>
    function positionChanged() {
        var position_id = $("#positionSelectList").val();
        if(!position_id){
            layer.msg('请先选择职位', {icon: 2});
            return false;
        }
        $.ajax({
            'url': "{{url('admin/getControllers')}}",
            'type': 'post',
            'data': {'position_id': position_id},
            'dataType': 'json',
            success: function (data) {
                if (data.status) {
                    $("#controllerList").html('');
                    $.each(data.msg, function (key, item) {
                        $("#controllerList").append('<li class="list-group-item" onclick="showFunctions('+item.id+')">' + item.controller + '' +
                            '<span class="badge" style="float: right"><button onclick="delCF(0,'+item.id+')"><i class="icon iconfont"></i></button></span></li>');
                    });
                }
            }
        });
    }

    function addController() {
        var position_id = $("#positionSelectList").val();
        if (!position_id) {
            layer.msg('请先选择职位再添加控制器', {icon: 2});
            return false;
        }
        var controller = $("#controller").val();

        $.ajax({
            'url': "{{url('admin/addController')}}",
            'type': 'post',
            'data': {'position_id': position_id, 'controller': controller},
            'dataType': 'json',
            success: function (data) {
                if(data.status){
                    positionChanged();
                    layer.msg(data.msg, {icon: data.icon});
                }
            }
        });


    }

    function showFunctions(controllerID){
        $("#currentControllerID").val('');


        $.ajax({
           'url':"{{url('admin/getFuncs')}}",
            'type':'post',
            'data':{'controller_id':controllerID},
            'dataType':'json',
            success:function(data){
               if(data.status){
                   $("#currentControllerID").val(controllerID);
                   $("#selectedControllerName").html(data.controllerName);
                   $("#funcsList").html('');
                   $.each(data.msg,function(key,item){
                       $("#funcsList").append('<li class="list-group-item" >' + item.function +
                           '<span class="badge" style="float: right"><button onclick="delCF(1,'+item.id+')"><i class="icon iconfont"></i></button></span></li>');

                   });

               }

            }
        });
    }

    function addFunction(){
        var controller_id = $("#currentControllerID").val();

        if(!controller_id){
            layer.msg('请先选择控制器再添加方法', {icon: 2});
            return false;
        }

        var functionName = $("#functionName").val();
        $.ajax({
            'url':"{{url('admin/addFunc')}}",
            'type':'post',
            'data':{'controller_id':controller_id,'function':functionName},
            'dataType':'json',
            success:function(data){
                if(data.status){
                    showFunctions(controller_id);
                }

                layer.msg(data.msg, {icon:data.icon});

            }
        });
    }


    function showModifyControllerFuncsModal() {
        for( var i=0;i<20;i++){
            $("#func"+i).val('');
            $("#for"+i).val('');
        }
        $.ajax({
           "url":"{{url('admin/getAllControllers')}}",
            "type":'post',
            'dataType':'json',
            success:function(data){
               if(data.status){
                   $("#controllerSelection").html('<option value="" selected disabled>选择控制器</option>');
                   $.each(data.msg,function(key,item){
                       $("#controllerSelection").append('<option value="'+item.id+'">'+item.controller+'</option>');
                   });





               }else{
                   layer.msg(data.msg,{icon:data.icon});
               }
            }
        });




        $("#modifyControllerFuncs").modal('show');
    }

    function getSelectedControllerFuncs() {
        var controllerId = $("#controllerSelection").val();
        if(!controllerId){
            return false;
        }

        $.ajax({
           'url':"{{url('admin/getFuncs')}}",
            'type':'post',
            'data':{'controller_id':controllerId},
            'dataType':'json',
            success:function(data){
               if(data.status){
                   $.each(data.msg,function(key,item){
                       $("#func"+key).val(item.function);
                       $("#for"+key).val(item.comment);
                   });
                   $("#modifyModalController").val(data.controllerName);
               }

            }
        });



    }


    function showAddControllerFuncsModal(){
        for( var i=0;i<20;i++){
            $("#funcs"+i).val('');
            $("#fors"+i).val('');
        }
        $("#controllerName").val('');

        $("#newControllerFuncs").modal('show');
    }

    function addControllerFunc(formid=null) {

        if(formid){

            var formData = $("#"+formid).serialize();
        }else{
            if(!$("#controllerName").val()){
                layer.msg('控制器名字不能为空',{icon:2});
                return false;

            }
            var formData = $("#controllerFuncsForm").serialize();
        }


        $.ajax({
           'url':"{{url('admin/addCommonControllerFuncs')}}",
           'type':'post',
           'data':formData,
           'dataType':'json',
           success:function(data){
               if(data.status){
                   $("#newControllerFuncs").modal('hide');
                   $("#modifyControllerFuncs").modal('hide');
               }
               layer.msg(data.msg,{icon:data.icon});

           }
        });

    }

    function delCF(type,id){
        $.ajax({
           'url':"{{url('admin/delCF')}}",
           'type':'post',
           'data':{'type':type,'id':id},
           'dataType':'json',
           success:function(data){
               layer.msg(data.msg,{icon:data.icon});
           }
        });

    }


</script>