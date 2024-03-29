@extends('admin/layout/basic')

@section('shortcut')
    <a href="javascript:void(0);" onclick="new_staff()">添加员工</a>
    <a href="javascript:void(0);" onclick="new_department()">添加部门</a>
    <a href="javascript:void(0);" onclick="edit_department()">修改部门</a>
    <a href="javascript:void(0);" onclick="new_position()">添加职位</a>
    <a href="javascript:void(0);" onclick="edit_position()">修改职位</a>
@endsection

@section('content')
<?php
//dd("FFF");
//        dd(file_get_contents(url(Route::current()->uri())));
//$content = json_decode(file_get_contents(url(Route::current()->uri())),true);
//$content = file_get_contents(url(Route::current()->uri()));
//dd($content);


?>

    <div class="x-body">


        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            </button>
            <span class="x-right" style="line-height:40px">共有数据：{{$staffCount}} 条</span>
        </xblock>




        <table id="staffListTable" class="table">
            <thead>
            <tr>
                <th>
                    <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                </th>
                <th>姓名</th>
                <th>工号</th>
                <th>身份证</th>
                <th>入职日期</th>
                <th>出生日期</th>
                <th>工作手机</th>
                <th>工作微信</th>
                <th>私人手机</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                
                @foreach($staffList as $staff)
                <tr>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$staff->staff_id}}'><i
                                    class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td>{{$staff->staff_name}}</td>
                    <td>{{$staff->staff_no}}</td>
                    <td>{{$staff->staff_id_no}}</td>
                    <td>{{$staff->staff_join_date}}</td>
                    <td>{{$staff->staff_dob}}</td>
                    <td>{{$staff->staff_mobile_work}}</td>
                    <td>{{$staff->staff_wenxin_work}}</td>
                    <td>{{$staff->staff_mobile_private}}</td>


                    <td class="td-manage">
                        <a title="查看" onclick="showStaff({{$staff->staff_id}})" href="javascript:;">
                            <i class="icon iconfont">&#xe69e;</i>
                        </a>
                        <a title="设置登录信息" onclick="showStaffLoginInfo({{$staff->staff_id}})" href="javascript:;">
                            <i class="icon iconfont">&#xe82b;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="delStaff({{$staff->staff_id}})">
                            <i class="layui-icon">&#xe640;</i>
                        </a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="page">
            <div>

            </div>
        </div>

    </div>


    <div class="modal fade  bd-example-modal-lg" id="staffModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffModalTitle">添加员工</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-6 col-sm-6">
                            <img id="staff_photo" src="" class="img-fluid rounded"/>
                            <input type="hidden" id="staff_photo_old" value=""/>

                        </div>
                        <div class="col-6 col-sm-6">
                            <div class="input-group input-group-sm mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="upload_staff_photo">
                                    <label class="custom-file-label" for="inputGroupFile04">员工照片</label>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>员工编号</span>
                                </div>
                                <input type="text" class="form-control" id="staff_no" aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>
                                <select class="custom-select  custom-select-sm" id="staff_status">
                                    <option value="正常" selected>正常</option>
                                    <option value="离职">离职</option>
                                </select>

                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>姓名</span>
                                </div>
                                <input type="text" class="form-control" id="staff_name"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>
                                <select class="custom-select  custom-select-sm" id="staff_gender">
                                    <option value="女" selected>女</option>
                                    <option value="男">男</option>
                                </select>

                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>民族</span>
                                </div>
                                <input type="text" class="form-control" id="staff_nationality"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>

                                <select class="custom-select  custom-select-sm" id="staff_marriage">
                                    <option value=null selected disabled>婚姻状况*</option>
                                    <option value="未婚">未婚</option>
                                    <option value="已婚">已婚</option>
                                    <option value="离异">离异</option>
                                    <option value="丧偶">丧偶</option>
                                </select>

                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>出生日期</span>
                                </div>
                                <input type="date" class="form-control" id="staff_dob"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01"><span style ="color: red;">*</span>政治面貌</label>
                                </div>
                                <input type="text" class="form-control" id="staff_political"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                        </div>

                    </div>



                    <hr/>

                    <div class="row">
                        <div class="col-6">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>身份证</span>
                                </div>
                                <input type="text" class="form-control" id="staff_id_no"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>


                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>籍贯</span>
                                </div>
                                <input type="text" class="form-control" id="staff_jiguan"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>私人手机</span>
                                </div>
                                <input type="text" class="form-control" id="staff_mobile_private"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">私人微信</span>
                                </div>
                                <input type="text" class="form-control" id="staff_wenxin_private"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">私人邮箱</span>
                                </div>
                                <input type="text" class="form-control" id="staff_email_private"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                        </div>
                        <div class="col-6">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>紧急联系人姓名</span>
                                </div>
                                <input type="text" class="form-control" id="staff_kin_name"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>紧急联系人关系</span>
                                </div>
                                <input type="text" class="form-control" id="staff_kin_relation"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>紧急联系人电话</span>
                                </div>
                                <input type="text" class="form-control" id="staff_kin_mobile"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>工作手机</span>
                                </div>
                                <input type="text" class="form-control" id="staff_mobile_work"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">工作微信</span>
                                </div>
                                <input type="text" class="form-control" id="staff_wenxin_work"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>

                            </div>


                        </div>


                        <div class="col-12">
                            <div class="input-group input-group-sm mb-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">地址</span>
                                </div>
                                <input type="text" class="form-control" id="staff_address"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>职位</span>
                                </div>

                                <select class="custom-select  custom-select-sm" id="position_id"
                                        onchange="getStaffLevel()">
                                    <option selected disabled value=null>请选择</option>
                                    @foreach($positions as $position)
                                        <option value="{{$position->id}}">{{$position->position_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>部门</span>
                                </div>

                                <select class="custom-select  custom-select-sm" id="department_id"
                                        onchange="getManagerName()">
                                    <option selected disabled value=null>请选择</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->depart_name}}">{{$department->depart_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>主管</span>
                                </div>

                                <select class="custom-select  custom-select-sm" id="staff_manager">
                                    <option value="0" selected>无上级主管</option>
                                    @foreach($managers as $manager)
                                    <option value ="{{$manager->staff_id}}">{{$manager->staff_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">工资</span>
                                </div>
                                <input type="text" class="form-control" id="staff_salary"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">.00 元</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">提成</span>
                                </div>
                                <input type="text" class="form-control" id="staff_commission_rate"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>员工类型</span>
                                </div>
                                <select class="custom-select  custom-select-sm" id="staff_type">
                                    <option value="试用员工" selected>试用员工</option>
                                    <option value="正式员工">正式员工</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">合同号码</span>
                                </div>
                                <input type="text" class="form-control" id="staff_contract_no"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">合同开始</span>
                                </div>
                                <input type="date" class="form-control" id="staff_contract_start"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">合同结束</span>
                                </div>
                                <input type="date" class="form-control" id="staff_contract_end"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><span style ="color: red;">*</span>入职日期</span>
                                </div>
                                <input type="date" class="form-control" id="staff_join_date"
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
                                              id="staff_edu_history" rows="6">
起止时间:
毕业院校:
专业:
证明人:
联系方式: </textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">工作经历<br/>

                                        </span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="staff_work_exp" rows="7">
起止时间:
单位名称:
职位:
离职原因:
证明人:
联系方式: </textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">证书/荣誉</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="staff_achievement" rows="4">
获奖时间:
证书/荣誉:
颁发机关: </textarea>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">家庭成员</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="staff_family_member" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">兴趣爱好</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="staff_hobby"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">自我评价</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="staff_self_assessment"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">主管评价</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="staff_assessment"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>


                    <input type="hidden" id="staff_level"/>
                    <input type="hidden" id="staff_id"/>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="seller_id" id="seller_id" value="test"/>
                    <button type="button" class="btn btn-danger" id="del_btn" >删除</button>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="add_staff()">保存修改</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staffLoginInfoModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellerModalTitle">OA员工登录信息</h5>
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
                                    <span class="input-group-text" id="inputGroup-sizing-sm">登录密码</span>
                                </div>
                                <input type="text" class="form-control" id="staff_login_password"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="login_staff_id" id="login_staff_id" />
                    <input type="hidden" name="login_staff_name" id="login_staff_name" />
                    <input type="hidden" name="login_staff_no" id="login_staff_no" />
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="saveStaffLoginInfo()">保存修改
                    </button>
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
                                <select class="custom-select  custom-select-sm" id="position_rank">
                                    <option value="普通员工" selected>普通员工</option>
                                    <option value="部门经理">部门经理</option>
                                    <option value="区域经理">区域经理</option>
                                    <option value="总经理">总经理</option>
                                    <option value="OA管理员">OA管理员</option>

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

                                        <select class="custom-select  custom-select-sm"
                                                id="positionRank{{$position->id}}">
                                            <option value="{{$position->position_rank}}"
                                                    selected>{{$position->position_rank}}</option>
                                            <option value="普通员工">普通员工</option>
                                            <option value="部门经理">部门经理</option>
                                            <option value="区域经理">区域经理</option>
                                            <option value="总经理">总经理</option>
                                            <option value="OA管理员">OA管理员</option>

                                        </select>

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



    <script type="text/javascript" language="javascript" class="init">
        $(document).ready(function() {
            $("#staffListTable").DataTable();
        });
    </script>



@endsection

<script>


    function refreshPage() {
        setTimeout(function () {
            window.location.reload();
        }, 800);

    }

    function initStaffModel(Staff){
        if(Staff){
            var modalTitle="修改员工信息";
            $("#staff_photo").attr('src',"/storage/staff/photo/"+Staff.staff_photo);

            $("#del_btn").attr('onclick',"delStaff("+Staff.staff_id+")");

            $("#staff_manager").val(Staff.staff_manager?Staff.staff_manager:0);
        }else{
            var modalTitle="添加员工信息";
            $("#staff_photo").attr('src',"");
        }
        $("#staffModalTitle").html(modalTitle);

        $("#staff_id").val(Staff.staff_id);
        $("#staff_no").val(Staff.staff_no);
        $("#staff_status").val(Staff.staff_status?Staff.staff_status:"正常");
        $("#staff_name").val(Staff.staff_name);
        $("#staff_no").val(Staff.staff_no);
        $("#staff_gender").val(Staff.staff_gender?Staff.staff_gender:"女");
        $("#staff_nationality").val(Staff.staff_nationality);
        $("#staff_marriage").val(Staff.staff_marriage?Staff.staff_marriage:null);
        $("#staff_dob").val(Staff.staff_dob);
        $("#staff_political").val(Staff.staff_political);
        $("#position_id").val(Staff.position_id?Staff.position_id:null);

        $("#department_id").val(Staff.department_id?Staff.department_id:null);
        $("#staff_manager").val(Staff.staff_manager?Staff.staff_manager:0);
        $("#staff_id_no").val(Staff.staff_id_no);
        $("#staff_jiguan").val(Staff.staff_jiguan);
        $("#staff_mobile_private").val(Staff.staff_mobile_private);
        $("#staff_wenxin_private").val(Staff.staff_wenxin_private);
        $("#staff_email_private").val(Staff.staff_email_private);
        $("#staff_kin_name").val(Staff.staff_kin_name);
        $("#staff_kin_relation").val(Staff.staff_kin_relation);
        $("#staff_kin_mobile").val(Staff.staff_kin_mobile);
        $("#staff_mobile_work").val(Staff.staff_mobile_work);
        $("#staff_wenxin_work").val(Staff.staff_wenxin_work);
        $("#staff_address").val(Staff.staff_address);
        $("#staff_salary").val(Staff.staff_salary);
        $("#staff_commission_rate").val(Staff.staff_commission_rate);
        $("#staff_type").val(Staff.staff_type?Staff.staff_type:"试用员工");
        $("#staff_join_date").val(Staff.staff_join_date);
        $("#staff_contract_no").val(Staff.staff_contract_no);
        $("#staff_contract_start").val(Staff.staff_contract_start);
        $("#staff_contract_end").val(Staff.staff_contract_end);
        $("#staff_edu_history").val(Staff.staff_edu_history);
        $("#staff_work_exp").val(Staff.staff_work_exp);
        $("#staff_family_member").val(Staff.staff_family_member);
        $("#staff_achievement").val(Staff.staff_achievement);
        $("#staff_hobby").val(Staff.staff_hobby);
        $("#staff_self_assessment").val(Staff.staff_self_assessment);
        $("#staff_assessment").val(Staff.staff_assessment);
        $("#staff_level").val(Staff.staff_level);
    }

    function showStaffLoginInfo(Staff){

        $("#staff_login_password").val('');
        $.ajax({
           'url':"{{url('admin/getStaffLoginInfo')}}/"+Staff,
           'type':'get',
           'dataType':'json',
           success:function(data){

               if(data.status){
                   $("#login_staff_id").val(data.info.staff_id);
                   $("#login_staff_name").val(data.info.staff_name);
                   $("#login_staff_no").val(data.info.staff_no);
               }else{
                   $("#login_staff_id").val('');
                   $("#login_staff_name").val('');
                   $("#login_staff_no").val('');
                   layer.msg(data.msg,{icon:data.icon});
               }
           }
        });

        $("#staffLoginInfoModal").modal('show');
    }

    function saveStaffLoginInfo(){
        var data = new FormData;
        data.append('staff_id',$("#login_staff_id").val());
        data.append('name',$("#login_staff_name").val());
        data.append('staff_no',$("#login_staff_no").val());
        data.append('password',$("#staff_login_password").val());


        $.ajax({
           'url':"{{url('admin/saveStaffLoginInfo')}}",
           'type':'post',
            'contentType': false,
            'processData': false,
           'data':data,
           'dataType':'json',
           success:function(data){
               layer.msg(data.msg, {icon: data.icon});
               $("#staffLoginInfoModal").modal('hide');
           }
        });
    }

    function delAll () {
        var data = tableCheck.getData();
        $.ajax({
            'url':"{{url('admin/staff')}}",
            'type':'delete',
            'dataType':'json',
            'data':{'staffIds':data},
            success:function(data){
                layer.msg(data.msg, {icon: data.icon});
                if(data){
                    refreshPage();
                }
            }
        });

    }

    function delStaff(staffID) {
        $.ajax({
           'url':"{{url('admin/staff')}}/"+staffID,
           'type':'delete',
           'dataType':'json',
           success:function(data){
               layer.msg(data.msg, {icon: data.icon});
               if(data){
                // refreshPage();
               }
           }
        });


    }

    function showStaff(staffID){
        if(staffID){
            $.ajax({
                'url':'{{url('admin/staff')}}/'+staffID,
                'type':'get',
                'dataType':'json',
                success:function(data){
                    if(data.status){

                        initStaffModel(data.staff);
                        $("#staffModal").modal('show');
                    }else{
                        layer.msg(data.msg, {icon: data.icon});
                    }
                }
            });
        }
    }

    function getStaffLevel() {
        var position_id = $("#position_id").val();
        $.ajax({
            'url': '{{url('admin/getStaffLevel')}}',
            'type': 'post',
            'data': {'position_id': position_id},
            'dataType': 'json',
            success: function (data) {
                $("#staff_level").val(data);
            }
        });
    }

    function getManagerName() {
        var department_id = $("#department_id").val();
        var position_id = $("#position_id").val();

        $.ajax({
            'url': "{{url('admin/getManagers')}}",
            'type': 'post',
            'data': {'department_id': department_id, 'position_id': position_id},
            'dataType': 'json',
            success: function (data) {
                $("#staff_manager").html('');
                $("#staff_manager").append(' <option value="0" selected>无上级主管</option>');
                $.each(data, function (key, item) {
                    $("#staff_manager").append('<option value="' + item.staff_id + '">' + item.staff_name + '</option>');
                });
            }
        });


    }

    function new_position() {
        $("#newPositionName").val('');
        $("#newPositionModal").modal('show');
    }

    function new_position_save() {
        var positionName = $("#newPositionName").val();
        var positionRank = $("#position_rank").val();

        $.ajax({
            'url': '{{url('admin/newPosition')}}',
            'type': 'post',
            'data': {'position_name': positionName, 'position_rank': positionRank},
            'dataType': 'json',
            success: function (data) {
                if (data.status) {
                    layer.msg(data.msg, {icon: data.icon});
                    $("#newDepartmentModal").modal('hide');
                    refreshPage()
                } else {
                    $(".afterProcess").html('<div class="alert alert-danger" role="alert">' + data.msg + '</div>');
                }
            }
        });

    }

    function new_positions_save(maxPositionId) {
        var data = new FormData;

        for (i = 1; i <= maxPositionId; i++) {
            var position = $("#positionName" + i).val() + "/" + $("#positionRank" + i).val();
            if ($("#positionRank" + i).val()) {
                data.append(i, position);
            }
        }


        $.ajax({
            'url': "{{url('admin/modifyPosition')}}",
            'type': 'post',
            'contentType': false,
            'processData': false,
            'data': data,
            'dataType': 'json',
            success: function (data) {
                if (data.status) {

                    $("#departmentModal").modal('hide');
                    refreshPage()
                }
                layer.msg(data.msg, {icon: data.icon});
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
                    layer.msg(data.msg, {icon: data.icon});
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

        for (var i = 1; i <= maxDepartId; i++) {
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
                layer.msg(data.msg, {icon:data.icon});
                $("#departmentModal").modal('hide');
                refreshPage()
            }
        });


    }

    function new_staff() {
        initStaffModel('');
        //初始化
        $('#staffModal').modal('show');

    }

    function add_staff() {
        //get staff info

        var data = new FormData;
        data.append('staff_id', $("#staff_id").val());
        data.append('upload_staff_photo', document.getElementById('upload_staff_photo').files[0]);
        data.append('staff_no', $("#staff_no").val());
        data.append('staff_status', $("#staff_status").val());
        data.append('staff_name', $("#staff_name").val());
        data.append('staff_no', $("#staff_no").val());
        data.append('staff_gender', $("#staff_gender").val());
        data.append('staff_nationality', $("#staff_nationality").val());
        data.append('staff_marriage', $("#staff_marriage").val());
        data.append('staff_dob', $("#staff_dob").val());
        data.append('staff_political', $("#staff_political").val());
        data.append('position_id', $("#position_id").val());
        data.append('department_id', $("#department_id").val());
        data.append('staff_manager', $("#staff_manager").val());
        data.append('staff_id_no', $("#staff_id_no").val());
        data.append('staff_jiguan', $("#staff_jiguan").val());
        data.append('staff_mobile_private', $("#staff_mobile_private").val());
        data.append('staff_wenxin_private', $("#staff_wenxin_private").val());
        data.append('staff_email_private', $("#staff_email_private").val());
        data.append('staff_kin_name', $("#staff_kin_name").val());
        data.append('staff_kin_relation', $("#staff_kin_relation").val());
        data.append('staff_kin_mobile', $("#staff_kin_mobile").val());
        data.append('staff_mobile_work', $("#staff_mobile_work").val());
        data.append('staff_wenxin_work', $("#staff_wenxin_work").val());
        data.append('staff_address', $("#staff_address").val());
        data.append('staff_salary', $("#staff_salary").val());
        data.append('staff_commission_rate', $("#staff_commission_rate").val());
        data.append('staff_type', $("#staff_type").val());
        data.append('staff_contract_no', $("#staff_contract_no").val());
        data.append('staff_join_date', $("#staff_join_date").val());
        data.append('staff_contract_start', $("#staff_contract_start").val());
        data.append('staff_contract_end', $("#staff_contract_end").val());
        data.append('staff_edu_history', $("#staff_edu_history").val());
        data.append('staff_work_exp', $("#staff_work_exp").val());
        data.append('staff_family_member', $("#staff_family_member").val());
        data.append('staff_achievement', $("#staff_achievement").val());
        data.append('staff_hobby', $("#staff_hobby").val());
        data.append('staff_self_assessment', $("#staff_self_assessment").val());
        data.append('staff_assessment', $("#staff_assessment").val());
        data.append('staff_level', $("#staff_level").val());

        $.ajax({
            'url': '{{url('admin/newStaff')}}',
            'type': 'post',
            'data': data,
            'dataType': 'json',
            'contentType': false,
            'processData': false,
            success: function (data) {
                if (data.status) {
                    $("#staffModal").modal('hide');
                    refreshPage();

                } else {

                    if (data.emptyCols) {
                        $.each(data.emptyCols, function (key, item) {
                            $("#" + item).addClass("is-invalid");
                        });
                    }
                }

                layer.msg(data.msg, {icon: data.icon});

            }
        });


    }

</script>
