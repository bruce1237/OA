@extends('admin/layout/basic')

@section('shortcut')
    <a href="javascript:void(0);" onclick="showAddMenu()">添加菜单</a>

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
            <div class="card border-success mb-3">
                <div class="card-header bg-transparent border-success" id="menuTitle">
                    请选择职位
                </div>
                <ul class="list-group list-group-flush bg-secondary" id="menuList">

                </ul>
            </div>


        </div>
        <div class="col-4">
            <div class="card border-primary mb-3">
                <div class="card-header bg-transparent border-primary" id="subMenuTitle">
                    请点击左侧你要查看的菜单
                </div>
                <ul class="list-group list-group-flush bg-secondary" id="subMenuList">

                </ul>
            </div>

        </div>
        <div class="col-4">
            <div class="card border-danger mb-3" id="accessArea" style="display:none">
                <div class="card-header bg-transparent border-danger" id="accessTitle">
                    权限处理
                </div>
                <ul class="list-group list-group-flush bg-danger" id="accessList">
                    <li class="list-group-item d-flex justify-content-between align-items-center">123</li>
                </ul>

                <div class="card-footer bg-transparent border-danger">


                    <div class="input-group input-group-sm mb-3">

                        <input type="text" class="form-control" id="accessName"
                               aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn-sm" id="save_btn" onclick="addAccess()">添加权限</button>
                        </div>



                    </div>


                </div>

                <input type="hidden" id="AccessSubmenuID" />
                <input type="hidden" id="AccessSubmenuName" />
                <input type="hidden" id="AccessSubmenuURL" />
                <input type="hidden" id="AccessMenuId" />
                <input type="hidden" id="AccessMenuName" />
            </div>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 10px;"></div>


    <div class="row">
        <div class="col-4">
            <div id="menuSection"></div>
        </div>
        <div class="col-4">
            <div id="submenuSection">

            </div>
        </div>
        <div class="col-4"></div>
    </div>

<input type="hidden" id ="menuListOrder" >
<input type="hidden" id ="subMenuListOrder" >




    <div class="modal fade" id="newMenu" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">主菜单</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="afterProcess"></div>
                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">菜单名称:</span>
                                </div>
                                <input type="text" class="form-control" id="newMenuName"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>

                                {{--<select class="form-control" id="MenuPosition222">--}}
                                    {{--@if($positions)--}}
                                        {{--@foreach($positions as $position)--}}
                                            {{--<option value="{{$position->id}}">{{$position->position_name}} </option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}

                                {{--</select>--}}

                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" id ="MenuPosition" />
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="addNewMenu()">添加</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newSubmenu" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submenuMenuName">新建子菜单</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="afterProcess"></div>
                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">添加子菜单</span>
                                </div>
                                <input type="text" class="form-control" id="newSubmenuName"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="menuName" id="menuName" value="test"/>
                    <input type="hidden" name="menuId" id="menuId" value="test"/>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="addNewSubmenu()">保存修改
                    </button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="newUrl" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submenuName">添加链接</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="afterProcess"></div>
                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="url">URL:</span>
                                </div>
                                <input type="text" class="form-control" id="submenuUrl"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">

                    <input type="hidden" name="submenuId" id="menuId_menuName" value="test"/>
                    <input type="hidden" name="submenuId" id="menuId_submenuId" value="test"/>
                    <input type="hidden" name="submenuId" id="submenuId" value="test"/>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="addURL()">保存链接</button>
                </div>
            </div>
        </div>
    </div>




    <script>


        function liSortableInit(containerID) {

            // sort_define = '';  //列表的初始值


            $("#" + containerID).sortable();
            $("#" + containerID).disableSelection();

            //获取列表的初始顺序
            // $("#" + containerID).sortable({
            //     start:
            //         function (event, ui) {
            //             var sort = '';
            //             $(this).find('li').each(function () {
            //                 var id = $(this).attr('data-id');
            //                 if (sort == "") {
            //                     sort = id;
            //                 } else {
            //                     if (typeof(id) != 'undefined')
            //                         sort = sort + '_' + id
            //                 }
            //             });
            //             sort_define = sort;
            //         }
            // });


            $("#" + containerID).sortable({
                stop:
                    function (event, ui) {
                        var sort = '';
                        $(this).find('li').each(function () {
                            var id = $(this).attr('data-id');
                            if (sort == "") {
                                sort = id;
                            } else {
                                if (typeof(id) != 'undefined')
                                    sort = sort + '_' + id;
                            }
                        });

                        $("#"+containerID+"Order").val(sort);
                    }

            });
        }


        function saveMenu(containerID) {

            var orderList = $("#"+containerID+'Order').val();

            if(!orderList){
              layer.msg("顺序没有改变, 不用保存",{icon:1});
              return false;
            }

            $.ajax({
               'url':'{{url('admin/menuOrder')}}',
               'type':'post',
               'data':{'menuName':containerID,'menuOrder':orderList},
               'dataType':'json',
               success:function(data){
                   layer.msg(data.msg,{icon:1});
               }
            });

        }


        function showSubMenu(menuId, menu) {
            if (!menuId) {
                return false;
            }

            $("#subMenuTitle").html(menu);

            $.ajax({
                'url': "{{url('admin/getSubMenu')}}",
                'type': 'post',
                'data': {'id': menuId},
                'dataType': 'json',
                success: function (data) {

                    if (data.status) {
                        $("#subMenuList").html('');
                        $.each(data.submenuList, function (key, submenu) {
                            getAccessButton = "showAccess("+submenu.id+",'"+submenu.submenu_name+"','"+submenu.submenu_url+"',"+menuId+",'"+menu+"')";

                            buttonfunction="addUrl("+submenu.id+",'"+submenu.submenu_name+"','"+submenu.submenu_url+"',"+menuId+",'"+menu+"')";

                            $("#subMenuList").append('<li class="list-group-item d-flex justify-content-between align-items-center" data-id="' + submenu.id + '">' +
                                '<a href="javascript:;" onclick=' + getAccessButton + '>' + submenu.submenu_name + '</a> <span class="badge">' +
                                '<button onclick='+buttonfunction+'>' +
                                '<i class="icon iconfont">&#xe6f7;</i></button></span><span class="badge">' +
                                '<button onclick="delMenu('+submenu.id+',1,\''+submenu.submenu_name+'\')">' +
                                '<i class="icon iconfont">&#xe69d</i></button></span></li>');
                        });

                        liSortableInit('subMenuList');//初始化菜单的拖动功能


                        var buttonfunction = "saveMenu('subMenuList')";
                        $("#submenuSection").html('<button class="btn btn-success" onclick=' + buttonfunction + '>保存菜单</button>');


                        var buttonfunction = 'showAddSubmenu(' + menuId + ',"' + menu + '")';
                        $("#submenuSection").append('<button class="btn btn-outline-info float-right" onclick=' + buttonfunction + '>添加子菜单</button>');

                    }
                }
            });


        }


        function positionChanged() {
            //get menu list according to the position
            var positionId = $("#positionSelectList").val();
            var SelectedPositionName = $("#positionSelectList").find("option:selected").text();

            $("#subMenuList").html('');





            $.ajax({
                'url': "{{url('admin/MenuList')}}",
                'type': 'post',
                'data': {'id': positionId},
                'dataType': 'json',
                success: function (data) {
                    $('#menuList').find('li').remove();
                    if (data.status) {
                        $("#menuTitle").html(SelectedPositionName);

                        $.each(data.menuList, function (key, menu) {
                            var buttonfunction = 'showSubMenu(' + menu.id + ',"' + menu.menu_name + '")';
                            $('#menuList').append('<li class="list-group-item d-flex justify-content-between align-items-center" data-id="' + menu.id + '"><a href="javascript:;" onclick=' + buttonfunction + '>' + menu.menu_name + '</a> <span class="badge"><button onclick="delMenu('+menu.id+',0,\''+menu.menu_name+'\')"><i class="icon iconfont">&#xe69d</i></button></span></li>');
                        });


                        liSortableInit('menuList');//初始化菜单的拖动功能
                        var buttonfunction = "saveMenu('menuList')";
                        $("#menuSection").html('<button class="btn btn-success" onclick=' + buttonfunction + '>保存菜单</button>');

                        buttonfunction="showAddMenu("+positionId+",'"+SelectedPositionName+"')";
                        $("#menuSection").append('<button class="btn btn-outline-info float-right" onclick=' + buttonfunction + '>添加菜单</button>');

                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                }

            });
        }


        function showAddMenu(positionId, positionName) {
            $("#MenuPosition").val(positionId);

            $("#newMenu").modal('show');

        }

        function showAddSubmenu(menuId, menu) {
            $("#submenuMenuName").html(menu);
            $("#newSubmenuName").val('');
            $('#menuId').val(menuId);
            $('#menuName').val(menu);

            $("#newSubmenu").modal('show');

        }


        function addNewSubmenu() {
            var submenu_name = $("#newSubmenuName").val();
            var menuId = $("#menuId").val();
            var menu = $("#menuName").val();

            $.ajax({
                'url': "{{url('admin/addSubmenu')}}",
                'type': 'post',
                'data': {'submenu_name': submenu_name, 'menu_id': menuId},
                'dataType': 'json',
                success: function (data) {
                    if (data.status) {
                        showSubMenu(menuId, menu);
                        $("#newSubmenu").modal('hide');
                        liSortableInit('subMenuList');//初始化菜单的拖动功能
                    }

                    layer.msg(data.msg, {icon: 1});
                }
            });


        }

        function addNewMenu() {
            var menuName = $("#newMenuName").val();
            var position = $("#MenuPosition").val();

            $.ajax({
                'url': "{{url('admin/newMenu')}}",
                'data': {'menu_name': menuName, 'menu_position': position},
                'dataType': 'json',
                'type': 'post',
                success: function (data) {
                    if (data.status) {
                        $("#newMenu").modal('hide');
                    }
                    layer.msg(data.msg, {icon: 1});
                    positionChanged();
                }


            });

        }

        function delMenu(menuId,menuType,menu_name){
            $.ajax({
                'url':"{{url('admin/delMenu')}}",
                'type':'delete',
                'data':{'id':menuId,'type':menuType},
                'dataType':'json',
                success:function(data){
                   if(data.status){
                       layer.msg(data.msg,{icon:1});
                       positionChanged();
                       if(menuType==1){
                           showSubMenu(data.menuId,data.menuName);

                       }
                   }
                }

            });
        }

        function addUrl(submenuID,submenuName,submenuUrl,menuId,menu){
            $("#menuId_submenuId").val(menuId);
            $("#menuId_menuName").val(menu);

            $("#submenuId").val(submenuID);
            $("#submenuUrl").val(submenuUrl);
            $("#submenuName").text(submenuName+" 添加链接: ");
            $("#newUrl").modal('show');
        }

        function addURL(){
            var submenuId = $("#submenuId").val();
            var submenuUrl = $("#submenuUrl").val();
            var menuId = $("#menuId_submenuId").val();
            var menu = $("#menuId_menuName").val();
            $.ajax({
                'url':"{{url('admin/addUrl')}}",
                'type':'post',
                'data':{'id':submenuId,'submenu_url':submenuUrl},
                'dataType':'json',
                success:function(data){
                    if(data.status){
                        $("#newUrl").modal('hide');
                        layer.msg(data.msg,{icon:1});
                        showSubMenu(menuId,menu);
                    }
                }

            });
        }

        function showAccess(submenuId,submenuName,submenuURL,menuId,menuName) {
            $("#AccessSubmenuID").val(submenuId);
            $("#AccessSubmenuName").val(submenuName);
            $("#AccessSubmenuURL").val(submenuURL);
            $("#AccessMenuId").val(menuId);
            $("#AccessMenuName").val(menuName);
            var positionId = $("#positionSelectList").val();

            $("#accessTitle").html(submenuName);
            $("#accessTitle").append('<br /><div class="input-group input-group-sm mb-3"><input type="text" id="submenuRul" class="form-control" value="'+submenuURL+'"/></div>');

            $("#accessList").html('');

            //获取当前子菜单URL的权限
            $.ajax({
               'url':"{{url('admin/readAccess')}}",
               'type':'post',
               'data':{'submenuId':submenuId,'positionId':positionId,'submenuURL':submenuURL},
                'dataType':'json',
                success:function(data){
                   if(data.status){
                       //

                       $.each(data.msg,function(key, item){

                               $("#accessList").append('<li class="list-group-item d-flex justify-content-between align-items-center">' + item + '</li>');
                       });
                   }else{
                       $("#accessList").html('<li class="list-group-item d-flex justify-content-between align-items-center">无内容</li>');
                   }

                }
            });

            $("#accessArea").show();

        }


        function addAccess() {
            var positionId = $("#positionSelectList").val();
            var access = $("#accessName").val();
            var submenuId = $("#AccessSubmenuID").val();
            var submenuName = $("#AccessSubmenuName").val();
            var submenuURL = $("#AccessSubmenuURL").val();
            var menuId = $("#AccessMenuId").val();
            var menuName = $("#AccessMenuName").val();

            $.ajax({
               'url':"{{url('admin/addAccess')}}",
               'type':'post',
                'data':{'access':access,'positionId':positionId,'submenuId':submenuId,'submenuURL':submenuURL},
                'dataType':'json',
                success:function(data){
                    $("#accessName").val('');
                  layer.msg(data.msg,{icon:1});
                }
            });

            showAccess(submenuId,submenuName,submenuURL,menuId,menuName);




        }

    </script>

@endsection
