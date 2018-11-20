@extends('admin/layout/basic')

@section('shortcut')
    <a href="javascript:void(0);" onclick="new_staff()">添加员工</a>
    <a href="javascript:void(0);" onclick="new_department()">添加部门</a>
    <a href="javascript:void(0);" onclick="edit_department()">修改部门</a>
    <a href="javascript:void(0);" onclick="new_position()">添加职位</a>
    <a href="javascript:void(0);" onclick="edit_position()">修改职位</a>
@endsection

@section('content')



    <div class="x-body">


        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            </button>
            <span class="x-right" style="line-height:40px">共有数据： 条</span>
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

            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=''><i
                                class="layui-icon">&#xe605;</i></div>
                </td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>


                <td class="td-manage">
                    <a title="查看" onclick="edit_seller()" href="javascript:;">
                        <i class="icon iconfont">&#xe69e;</i>
                    </a>
                    <a title="删除" href="javascript:;">
                        <i class="layui-icon">&#xe640;</i>
                    </a>
                </td>
            </tr>


            </tbody>
        </table>
        <div class="page">
            <div>

            </div>
        </div>

    </div>


    <div class="modal fade  bd-example-modal-lg" id="staffModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">添加员工</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-6 col-sm-6">
                            <img id="logo_img" src="" class="img-fluid rounded"/>
                            <input type="hidden" id="staff_photo_old" value=""/>

                        </div>
                        <div class="col-6 col-sm-6">
                            <div class="input-group input-group-sm mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="staff_photo">
                                    <label class="custom-file-label" for="inputGroupFile04">员工照片</label>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">员工编号</span>
                                </div>
                                <input type="text" class="form-control" id="staff_no" aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>
                                <select class="custom-select  custom-select-sm" id="staff_gender">
                                    <option value="1" selected>正常</option>
                                    <option value="0">禁用</option>
                                </select>

                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">姓名</span>
                                </div>
                                <input type="text" class="form-control" id="staff_name"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>
                                <select class="custom-select  custom-select-sm" id="staff_gender">
                                    <option value="0" selected>女</option>
                                    <option value="1">男</option>
                                </select>

                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">民族</span>
                                </div>
                                <input type="text" class="form-control" id="logo_color"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>

                                <select class="custom-select  custom-select-sm" id="logo_category">
                                    <option value="" selected disabled>婚姻状况</option>
                                    <option value="0">未婚</option>
                                    <option value="1">已婚</option>
                                    <option value="2">离异</option>
                                    <option value="3">丧偶</option>
                                </select>

                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">出生日期</span>
                                </div>
                                <input type="date" class="form-control" id="suitable"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">政治面貌</label>
                                </div>
                                <input type="text" class="form-control" id="suitable"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                        </div>

                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">职位</span>
                                </div>

                                <select class="custom-select  custom-select-sm" id="logo_category">
                                    <option selected disabled>请选择</option>
                                   @foreach($positions as $position)
                                       <option value ="{{$position->id}}">{{$position->position_name}}</option>
                                       @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">部门</span>
                                </div>

                                <select class="custom-select  custom-select-sm" id="logo_category">
                                    <option  selected disabled>请选择</option>
                                    @foreach($departments as $department)
                                        <option value ="{{$department->id}}">{{$department->depart_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">主管</span>
                                </div>

                                <select class="custom-select  custom-select-sm" id="logo_category">
                                    <option selected disabled>请选择</option>
                                    @foreach($managers as $manager)
                                        <option value ="{{$manager->id}}">{{$manager->staff_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                    </div>


                    <hr/>

                    <div class="row">
                        <div class="col-6">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">身份证</span>
                                </div>
                                <input type="text" class="form-control" id="app_date"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">籍贯</span>
                                </div>
                                <input type="text" class="form-control" id="app_date"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">私人手机</span>
                                </div>
                                <input type="text" class="form-control" id="announcement_issue"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">私人微信</span>
                                </div>
                                <input type="text" class="form-control" id="announcement_issue"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">私人邮箱</span>
                                </div>
                                <input type="text" class="form-control" id="announcement_issue"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                        </div>
                        <div class="col-6">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">紧急联系人姓名</span>
                                </div>
                                <input type="text" class="form-control" id="logo_agent"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">紧急联系人关系</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_cn"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">紧急联系人电话</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_en"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">工作手机</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_id"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">工作微信</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_share"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>

                            </div>


                        </div>


                        <div class="col-12">
                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">地址</span>
                                </div>
                                <input type="text" class="form-control" id="seller_name"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-6">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">工资</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_share"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">.00 元</span>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">合同开始</span>
                                </div>
                                <input type="date" class="form-control" id="applicant_share"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>

                            </div>

                        </div>
                        <div class="col-6">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">奖金</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_share"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">%</span>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">合同结束</span>
                                </div>
                                <input type="date" class="form-control" id="applicant_share"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>

                            </div>

                        </div>
                    </div>

                    <hr/>
                    <div class="row">
                        <div class="col-12">

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">教育经历</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="goods_code" rows="6">
起止时间:
毕业院校:
专业:
证明人:
联系方式:</textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">工作经历<br/>

                                        </span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="goods_code" rows="7">
起止时间：
单位名称：
职位：
离职原因：
证明人：
联系方式：</textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">证书/荣誉</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="logo_flow" rows="4">
获奖时间：
证书/荣誉：
颁发机关：</textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">兴趣爱好</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="logo_flow"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">自我评价</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="logo_flow"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">主管评价</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="logo_flow"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">

                            <div class="alert alert-primary" role="alert">
                                添加时间：<span id="created_at"></span>
                                更新时间：<span id="updated_at"></span>
                            </div>


                        </div>
                    </div>


                    <input type="hidden" id="seller_id" value=""/>
                    <input type="hidden" id="goods_id" value=""/>
                    <input type="hidden" id="flow_id" value=""/>


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

    <div class="modal fade" id="newDepartmentModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">新建部门</h5>
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
                                    <span class="input-group-text" id="inputGroup-sizing-sm">部门名称</span>
                                </div>
                                <input type="text" class="form-control" id="newDepartmentName"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="seller_id" id="seller_id" value="test"/>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="new_department_save()">保存修改
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="departmentModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">部门管理</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">


                        @if(isset($departments))
                            @foreach($departments as $department)
                                <div class="col-12">
                                    <div class="input-group input-group-sm mb-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">部门名称</span>
                                        </div>
                                        <input type="text" class="form-control" id="depart_name{{$department->id}}"
                                               aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                               value="{{$department->depart_name}}"/>
                                    </div>
                                </div>
                                <hr/>
                            @endforeach
                        @else
                            <div class="alert alert-primary" role="alert">
                                您还没有添加部门，请先添加部门！
                            </div>
                        @endif


                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="seller_id" id="seller_id" value="test"/>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn"
                            onclick="new_departments_save({{$departmentsMaxId}})">保存修改
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="newPositionModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">添加职位</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="afterProcess"></div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">职位名称</span>
                                </div>
                                <input type="text" class="form-control" id="newPositionName"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                        <div class="col-6">

                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">行政级别</span>
                                </div>
                                <select class="custom-select  custom-select-sm" id="staff_gender">
                                    <option value="0" selected>普通员工</option>
                                    <option value="3">部门经理</option>
                                    <option value="7">区域经理</option>
                                    <option value="9">总经理</option>

                                </select>
                            </div>
                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="seller_id" id="seller_id" value="test"/>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="new_position_save()">保存修改
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="positionModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">职位管理</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        @if(isset($positions))
                            @foreach($positions as $position)

                                <div class="col-12">
                                    <div class="afterProcess"></div>
                                    <div class="input-group input-group-sm mb-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">职位名称</span>
                                        </div>
                                        <input type="text" class="form-control" id="positionName{{$position->id}}"
                                               aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                               value="{{$position->position_name}}"/>
                                    </div>
                                </div>
                                <hr/>
                            @endforeach
                        @else
                            <div class="alert alert-primary" role="alert">
{{--                                {{$positions}}--}}
                            </div>
                        @endif

                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn"
                            onclick="new_positions_save({{$positionsMaxId}})">保存修改
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    function refreshPage() {
        setTimeout( function(){window.location.reload();}, 800);

    }

    function new_position() {
        $("#newPositionName").val('');
        $("#newPositionModal").modal('show');
    }

    function new_position_save() {
        var positionName = $("#newPositionName").val();

        $.ajax({
            'url': '{{url('admin/newPosition')}}',
            'type': 'post',
            'data': {'position_name': positionName},
            'dataType': 'json',
            success: function (data) {
                if (data.status) {
                    layer.msg(data.msg, {icon: 1});
                    $("#newDepartmentModal").modal('hide');
                    refreshPage()
                } else {
                    $(".afterProcess").html('<div class="alert alert-danger" role="alert">' + data.msg + '</div>');
                }
            }
        });

    }

    function new_positions_save(maxPositionId) {
        data = new FormData;
        for (i = 1; i <= maxPositionId; i++) {
            var position = $("#positionName" + i).val();
            if (position !== undefined) {
                data.append(i, position);
            }
            // alert(position);
        }


        $.ajax({
            'url': "{{url('admin/modifyPositions')}}",
            'type': 'post',
            'contentType': false,
            'processData': false,
            'data': data,
            'dataType': 'json',
            success: function (data) {
                if(data.status){
                    layer.msg(data.msg, {icon: 1});
                    $("#departmentModal").modal('hide');
                    refreshPage()
                }
            }

        });


    }

    function edit_position() {
        $("#positionModal").modal('show');
    }


    function new_department() {
        $(".afterProcess").html('');
        $("#newDepartmentName").val('');
        $("#newDepartmentModal").modal('show');
    }

    function new_department_save() {

        var departmentName = $("#newDepartmentName").val();
        $.ajax({
            'url': "{{url('admin/newDepart')}}",
            'type': "post",
            'data': {'depart_name': departmentName},
            'dataType': 'json',
            success: function (data) {

                if (data.status) {
                    layer.msg(data.msg, {icon: 1});
                    $("#newDepartmentModal").modal('hide');
                    refreshPage()
                } else {
                    $(".afterProcess").html('<div class="alert alert-danger" role="alert">' + data.msg + '</div>');

                }
            }
        });

    }

    function edit_department() {
        $("#departmentModal").modal('show');
    }

    function new_departments_save(maxDepartId) {
        data = new FormData();

        for (i = 1; i <= maxDepartId; i++) {
            var department = $("#depart_name" + i).val();
            if (department != undefined) {
                data.append(i, department);
            }
        }

        $.ajax({
            'url': "{{url('admin/modifyDepart')}}",
            'type': 'post',
            'contentType': false,
            'processData': false,
            'data': data,
            'dataType': 'json',
            success: function (data) {
                layer.msg(data.msg, {icon: 1});
                $("#departmentModal").modal('hide');
                refreshPage()
            }
        });


    }

    function new_staff() {
        //初始化
        $('#staffModal').modal('show');

    }
</script>
