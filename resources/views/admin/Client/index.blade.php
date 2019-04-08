@extends ("admin/layout/basic")

@section('shortcut')
    <a class="layui-btn layui-btn btn-success layui-btn-xs" onclick="showNewClientModal()">
        <span style="color:white"><i class="layui-icon"></i>添加新客户</span>
    </a>

    <a class="layui-btn layui-btn btn-danger layui-btn-xs" href="{{url('admin/clientManage/new')}}">
        <span style="color:white"><i class="layui-icon"></i>新信息列表</span>
    </a>

    <a class="layui-btn layui-primary layui-btn-xs" href="{{url('admin/clientManage/overdue')}}">
        <span style="color:white"><i class="layui-icon"></i>逾期回访客户</span>
    </a>

    <a class="layui-btn layui-btn btn-warning layui-btn-xs" href="{{url('admin/clientManage/pending')}}">
        <span style="color:white"><i class="layui-icon"></i>待回访客户</span>
    </a>

    <a class="layui-btn layui-btn btn-info layui-btn-xs" href="{{url('admin/clientManage/all')}}">
        <span style="color:white"><i class="layui-icon"></i>全部信息</span>
    </a>
    <a class="layui-btn layui-btn btn-primary layui-btn-xs" href="{{url('admin/clientManage/pool')}}">
        <span style="color:white"><i class="layui-icon"></i>公海信息</span>
    </a>

    <a class="layui-btn layui-btn btn-primary layui-btn-xs" data-toggle="modal" data-target="#searchClientModal">
        <span style="color:white"><i class="layui-icon"></i>客户搜索</span>
    </a>

@endsection


@section('content')


    <div class="row">
        <div class="col-5">
            <div class="card border-success mb-12" id="clientList">
                <div class="card-header text-white {{$data['clients']['bg']}}"
                     id="clientListTitle">{{$data['clients']['list_name']}}</div>
                <div class="card-body text-secondary" id="dataTableDiv">
                    <xblock>
                        <button class="layui-btn layui-btn-danger" onclick="batchToPool()"><i class="layui-icon"></i>批量放入公海
                        </button>
                        @if($data['staffLevel'])
                            <button class="layui-btn layui-btn-success" onclick="showBatchToAssignModal()"><i
                                    class="layui-icon"></i>批量指派
                            </button>
                        @else
                            <button class="layui-btn layui-btn-success" onclick="batchToAssign()"><i
                                    class="layui-icon"></i>批量领取
                            </button>
                        @endif
                    </xblock>
                    {{--<table id="clientListTable" class="table table-sm table-hover">--}}
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>
                                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i
                                        class="layui-icon">&#xe605;</i></div>
                            </th>
                            <th>姓名</th>
                            <th>手机</th>
                            <th>创建时间</th>
                            <th>回访时间</th>
                            <th>销售情况</th>
                            <th>隶属人</th>
                        </tr>
                        </thead>
                        <tbody id="clientTableBody">

                        @foreach($data['clients']['clients'] as $client)

                            <tr id="clientList{{$client->client_id}}"
                                onclick="getClientDetail({{$client->client_id}})">
                                <td>
                                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary"
                                         data-id='{{$client->client_id}}'><i class="layui-icon">&#xe605;</i></div>
                                </td>
                                <td>{{$client->client_name}}@if($client->client_new_enquiries)<span
                                        id="newTag{{$client->client_id}}"
                                        class="badge badge-pill badge-danger">(NEW)</span>@endif</td>
                                {{--@if($client->client_new_enquiries)<span class="badge badge-pill badge-danger">(NEW)</span>@endif--}}
                                <td>{{$client->client_mobile}}</td>
                                <td>{{$client->created_at}}</td>
                                <td>{{$client->client_next_date}}</td>
                                <td><span
                                        class="badge badge-pill {{$client->visitColorCode}}">{{$client->client_visit_status}}</span>
                                </td>
                                <td>{{$client->client_assign_to}}</td>
                            </tr>
                        @endforeach

                        </tbody>

                    </table>

                    {!!  $data['clients']['clients']->appends($data['search'])->render() !!}
                </div>
            </div>


        </div>

        <div class="col-7">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">
                    <span id="clientDetailHeader"></span>
                    @if($data['staffLevel']>=env('DEPARTMENT_CHIEF_LEVEL'))
                        <span class="input-group input-group-sm mb-3" id="reassignStaff" style="display:none ">
                            <span class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">重新分配</span>
                            </span>
                            <select class="form-control" name="reassignClientTo" id="reassignClientTo"
                                    aria-label="Small"
                                    aria-describedby="inputGroup-sizing-sm" onchange="reassignClient()">
                                <option selected disabled>请选择业务员</option>
                                @foreach($data['assignableStaffs'] as $assignableStaff)
                                    <option
                                        value="{{$assignableStaff->staff_id}}">{{$assignableStaff->staff_name}}</option>
                                @endforeach
                            </select>
                        </span>
                    @endif
                </div>
                <div class="card-body text-primary" id="clientDetailBody">
                    客户需求 <input type="hidden" id="client_id"/>
                    <p class="card-text" id="client_enquiries"></p>

                    <form id="clientDetailForm">
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="inputGroup-sizing-sm"><strong>姓名</strong></span>
                                    </div>
                                    <input type="text" name="client_name" id="client_name" class="form-control"
                                           aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                    <select class="form-control" id="client_source" aria-label="Small"
                                            aria-describedby="inputGroup-sizing-sm">
                                        @foreach($data['clientSource'] as $source)
                                            <option
                                                value="{{$source->info_source_name}}">{{$source->info_source_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">手机</span>
                                    </div>
                                    <input type="text" name="client_mobile" id="client_mobile" class="form-control"
                                           aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">座机</span>
                                    </div>
                                    <input type="text" name="client_tel" id="client_tel" class="form-control"
                                           aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">隶属</span>
                                    </div>
                                    <select class="form-control" name="client_belongs_company"
                                            id="client_belongs_company" aria-label="Small"
                                            aria-describedby="inputGroup-sizing-sm">
                                        @foreach($data['firms'] as $firm)
                                            <option value="{{$firm->firm_name}}">{{$firm->firm_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">微信</span>
                                    </div>
                                    <input type="text" name="client_wechat" id="client_wechat" class="form-control"
                                           aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">QQ</span>
                                    </div>
                                    <input type="text" name="client_qq" id="client_qq" class="form-control"
                                           aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">邮箱</span>
                                    </div>
                                    <input type="text" name="client_email" id="client_email" class="form-control"
                                           aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-3">创建日期:<span id="created_at"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">地址</span>
                                    </div>
                                    <input type="text" name="client_address" id="client_address" class="form-control"
                                           aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">邮编</span>
                                    </div>
                                    <input type="text" name="client_post_code" id="client_post_code"
                                           class="form-control" aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-2">回访:<span id="visit_next_date"></span></div>
                            <div class="col-2">等级:<span id="client_level"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="companies"></div>
                        </div>
                    </form>
                    <br/>
                    <div class="col-12" id="uploadClientQlf" style="display: none">
                        <div class="input-group input-group-sm mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="client_qualification"
                                       multiple="multiple">
                                <label class="custom-file-label" for="inputGroupFile02">选择资质文件(多选)</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="" onclick="uploadClientQualification()">上传</button>
                            </div>
                        </div>
                    </div>

                    <div id="clientQlf"></div>


                </div>
            </div>


            <div class="card border-success mb-3">

                <div class="card-body text-success" style="max-height: 200px;overflow: auto">

                    <table class="table table-sm table-hover" style="font-size:small">
                        <thead>
                        <tr>
                            <th scope="col">回访时间</th>
                            <th scope="col">销售状况</th>
                            <th scope="col" width="40%">回访内容</th>
                            <th scope="col">下次回访日期</th>
                            <th scope="col">回访人</th>
                        </tr>
                        </thead>
                        <tbody id="visitHistory">
                        </tbody>
                    </table>

                </div>
                <div class="card-footer bg-transparent border-success">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">销售状态</span>
                        </div>

                        <select class="custom-select" id="visit_status" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">
                            @foreach($data['visitStatus'] as $visitStatus)
                                <option
                                    value="{{$visitStatus->visit_status_id}}">{{$visitStatus->visit_status_name}}</option>
                            @endforeach
                        </select>
                        <input type="text" id="visit_records" class="form-control" aria-label="Small"
                               aria-describedby="inputGroup-sizing-sm" placeholder="回访内容">
                        <input type="date" id="visit_visit_next_date" class="form-control" aria-label="Small"
                               aria-describedby="inputGroup-sizing-sm" placeholder="下次回访日期">
                        <button class="form-control btn btn-outline-success" type="button" onclick="addVisit()">添加回访
                        </button>
                    </div>


                </div>
            </div>

            <div class="card border-warning mb-3">
                <div class="card-body text-primary" style="max-height:300px; overflow: auto">
                    <table class="table table-sm table-hover" style="font-size:small">
                        <thead>
                        <tr>
                            <th scope="col">订单号</th>
                            <th scope="col">创建日期</th>
                            <th scope="col">金额/利润</th>
                            <th scope="col">状态</th>
                            <th scope="col">最后更新时间</th>
                            <th scope="col" width="50%">文件</th>
                        </tr>
                        </thead>
                        <tbody id="orderList">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


    <div class="modal" tabindex="-1" role="dialog" id="newCompanyModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">添加公司</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCompanyForm">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">公司名称:<i class="text-danger">*</i> </span>
                            </div>
                            <input type="text" name="company_name" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">公司网站:</span>
                            </div>
                            <input type="text" name="company_website" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">纳税识别号/个人身份证号:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" name="company_tax_id" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">银行账号:</span>
                            </div>
                            <input type="text" name="company_account_number" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">开户银行地址:</span>
                            </div>
                            <input type="text" name="company_account_address" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司地址:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" name="company_address" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司邮编:</span>
                            </div>
                            <input type="text" name="company_post_code" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>


                        <div class="input-group input-group-sm mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="company_qualification"
                                       multiple="multiple">
                                <label class="custom-file-label" for="inputGroupFile02">选择资质文件(多选)</label>
                            </div>

                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="addCompany()">添加</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="updateCompanyModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">修改公司</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateCompanyForm">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">公司名称:
                                    <input type="hidden" id="modify_company_id" name="company_id"/>
                                </span>
                            </div>
                            <input type="text" id="modify_company_name" name="company_name" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">公司网站:</span>
                            </div>
                            <input type="text" id="modify_company_website" name="company_website" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">纳税识别号/个人身份证号:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" id="modify_company_tax_id" name="company_tax_id" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">银行账号:</span>
                            </div>
                            <input type="text" id="modify_company_account_number" name="company_account_number"
                                   class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">开户银行地址:</span>
                            </div>
                            <input type="text" id="modify_company_account_address" name="company_account_address"
                                   class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司地址:</span>
                            </div>
                            <input type="text" id="modify_company_address" name="company_address" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司邮编:</span>
                            </div>
                            <input type="text" id="modify_company_post_code" name="company_post_code"
                                   class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="modify_company_qualification"
                                       multiple="multiple">
                                <label class="custom-file-label" for="inputGroupFile02">选择资质文件(多选)</label>
                            </div>

                        </div>
                    </form>
                    <div id="company_qualifications"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="modifyCompany()">修改</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="newClientModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="newClientForm">
                    <div class="modal-header">
                        <h5 class="modal-title">添加客户</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户隶属:</span>
                            </div>
                            <select class="form-control" name="client_belongs_company">
                                @foreach($data['firms'] as $firm)
                                    <option
                                        value="{{$firm['firm_name']}}">{{$firm['firm_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户来源: </span>
                            </div>
                            <select class="form-control" name="client_source">

                                @foreach($data['clientSource'] as $client_source)
                                    <option
                                        value="{{$client_source['info_source_id']}}">{{$client_source['info_source_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户姓名:</span>
                            </div>
                            <input type="text" class="form-control" name="client_name" placeholder="客户姓名"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户手机:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" class="form-control" name="client_mobile" placeholder="客户手机"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">咨询内容:</span>
                            </div>
                            <input type="text" class="form-control" name="client_enquiries" placeholder="咨询内容"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户地址:</span>
                            </div>
                            <input type="text" class="form-control" name="client_address" placeholder="咨询内容"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户邮编:</span>
                            </div>
                            <input type="text" class="form-control" name="client_post_code" placeholder="咨询内容"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户电话:</span>
                            </div>
                            <input type="text" class="form-control" name="client_tel" placeholder="咨询内容"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户微信:</span>
                            </div>
                            <input type="text" class="form-control" name="client_wechat" placeholder="咨询内容"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户QQ:</span>
                            </div>
                            <input type="text" class="form-control" name="client_qq" placeholder="咨询内容"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户电邮:</span>
                            </div>
                            <input type="text" class="form-control" name="client_email" placeholder="咨询内容"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary">重置</button>
                        <button type="button" class="btn btn-primary" onclick="addClient()">修改</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--    <div class="modal fade " tabindex="-1" role="dialog" id="clientQlfModal">--}}
    {{--        <div class="modal-dialog" role="document" style="max-width: 1200px;">--}}
    {{--            <div class="modal-content">--}}
    {{--                <form id="newClientForm">--}}
    {{--                    <div class="modal-header">--}}
    {{--                        <h5 class="modal-title" id="clientQLFTitle">客户资质</h5>--}}
    {{--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
    {{--                            <span aria-hidden="true">&times;</span>--}}
    {{--                        </button>--}}
    {{--                    </div>--}}
    {{--                    <div class="modal-body">--}}
    {{--                        <iframe id="clientQLFEMBD" style="width:1150px;height:600px;"></iframe>--}}
    {{--                    </div>--}}
    {{--                    <div class="modal-footer">--}}
    {{--                        <input type="hidden" id="clientQLFFileName"/>--}}
    {{--                        <button type="button" class="btn btn-primary" onclick="rmClientQLFFile()">删除</button>--}}
    {{--                    </div>--}}
    {{--                </form>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}


    <div class="modal fade" id="searchClientModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{url('admin/clientManage/search')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">搜索客户</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">客户类型</span>
                            </div>
                            <select class="form-control" name="search_clientType" id="search_clientType"
                                    aria-label="Small"
                                    aria-describedby="inputGroup-sizing-sm">
                                <option value="1">已有</option>
                                <option value="0">公海</option>
                                <option value="2">全部</option>

                            </select>


                        </div>


                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">选择部门</span>
                            </div>
                            <select class="form-control" name="department" id="search_department" aria-label="Small"
                                    aria-describedby="inputGroup-sizing-sm"
                                    onchange="getStaffByDepart('search_department','search_staff')">
                                <option value=0 selected>选择部门</option>
                                @foreach($data['departments'] as $department)
                                    <option
                                        value="{{$department->id}}">{{$department->depart_name}}</option>
                                @endforeach
                            </select>

                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"> : </span>
                            </div>

                            <select class="form-control" name="staff_id" id="search_staff" aria-label="Small"
                                    aria-describedby="inputGroup-sizing-sm">

                            </select>

                        </div>


                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">客户姓名</span>
                            </div>
                            <input type="text" name="client_name" class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">客户手机</span>
                            </div>
                            <input type="text" name="client_mobile" class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">销售状况</span>
                            </div>
                            <select class="form-control" name="client_visit_status" aria-label="Small"
                                    aria-describedby="inputGroup-sizing-sm">
                                <option value=0 selected>销售状况</option>
                                @foreach($data['visitStatus'] as $visitStatus)
                                    <option
                                        value="{{$visitStatus->visit_status_id}}">{{$visitStatus->visit_status_name}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">创建时间</span>
                            </div>
                            <input type="date" name="client_created_from" value="" class="form-control"
                                   aria-label="Username" aria-describedby="basic-addon1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">-</span>
                            </div>
                            <input type="date" name="client_created_to" class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><label for="create_order_asc">升序 </label>
                                    <input type="radio" id="create_order_asc" name="order_by" value="created_at,asc"
                                           aria-label="Radio button for following text input">
                                </div>
                            </div>
                            <div class="input-group-prepend">
                                <div class="input-group-text"><label for="create_order_desc">降序 </label>
                                    <input type="radio" id="create_order_desc" name="order_by" value="created_at,desc"
                                           aria-label="Radio button for following text input">
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">回访时间</span>
                            </div>
                            <input type="date" name="client_visit_from" class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">-</span>
                            </div>
                            <input type="date" name="client_visit_to" class="form-control" aria-label="Username"
                                   aria-describedby="basic-addon1">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><label for="visit_order_asc">升序 </label>
                                    <input type="radio" checked id="visit_order_asc" name="order_by"
                                           value="client_next_date,asc"
                                           aria-label="Radio button for following text input">
                                </div>
                            </div>
                            <div class="input-group-prepend">
                                <div class="input-group-text"><label for="visit_order_desc">降序 </label>
                                    <input type="radio" id="visit_order_desc" name="order_by"
                                           value="client_next_date,desc"
                                           aria-label="Radio button for following text input">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">搜索</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="batchAssignModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">批量指派</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">选择部门</span>
                        </div>
                        <select class="form-control" name="department" id="assign_department" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm"
                                onchange="getStaffByDepart('assign_department','assign_staff')">
                            <option value=0 selected>选择部门</option>
                            @foreach($data['departments'] as $department)
                                <option
                                    value="{{$department->id}}">{{$department->depart_name}}</option>
                            @endforeach
                        </select>

                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"> : </span>
                        </div>

                        <select class="form-control" name="staff_id" id="assign_staff" aria-label="Small"
                                aria-describedby="inputGroup-sizing-sm">

                        </select>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="batchToAssign()">批量指派</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="OrderModelTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="orderModelForm">

                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons"
                         id="OrderModelfirms">
                        @foreach($data['firms'] as $firm)
                            <label class="btn btn-outline-success"
                                   onclick="getPaymentMethodByFirm({{$firm->firm_id}})">
                                <input type="radio" id="firm_id" name="order_firm" value="{{$firm->firm_id}}"
                                       autocomplete="off"> {{$firm->firm_name}}
                            </label>
                        @endforeach
                    </div>
                    选择公司:
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons"
                         id="OrderModelCompany"></div>

                    <br/><br/>

                    <div class="input-group input-group-sm mb-3">
                        联系人: &nbsp;<div class="btn-group  btn-group-toggle" data-toggle="buttons"
                                        id="OrderModelContact"></div>&nbsp;
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">姓名</span>
                        </div>
                        <input type="text" class="form-control" id="order_contact_name" name="order_contact_name"
                               placeholder="联系人姓名" aria-label="Username" aria-describedby="basic-addon1">

                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">电话</span>
                        </div>
                        <input type="text" class="form-control" id="order_contact_number"
                               name="order_contact_number"
                               placeholder="联系人电话" aria-label="Username" aria-describedby="basic-addon1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">地址</span>
                        </div>
                        <input type="text" class="form-control" id="order_contact_address"
                               name="order_contact_address"
                               placeholder="联系人地址" aria-label="Username" aria-describedby="basic-addon1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">邮编</span>
                        </div>
                        <input type="text" class="form-control" id="order_contact_post_code"
                               name="order_contact_post_code" placeholder="联系人地址" aria-label="Username"
                               aria-describedby="basic-addon1">
                    </div>

                    <div class="input-group input-group-sm mb-3">
                        邮寄地址: &nbsp;<div class="btn-group  btn-group-toggle" data-toggle="buttons"
                                         id="OrderModelContact"></div>&nbsp;
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">姓名</span>
                        </div>
                        <input type="text" class="form-control" id="order_post_addressee"
                               name="order_post_addressee"
                               placeholder="联系人姓名" aria-label="Username" aria-describedby="basic-addon1">

                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">电话</span>
                        </div>
                        <input type="text" class="form-control" id="order_post_contact" name="order_post_contact"
                               placeholder="联系人电话" aria-label="Username" aria-describedby="basic-addon1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">地址</span>
                        </div>
                        <input type="text" class="form-control" id="order_post_address" name="order_post_address"
                               placeholder="联系人地址" aria-label="Username" aria-describedby="basic-addon1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">邮编</span>
                        </div>
                        <input type="text" class="form-control" id="order_post_code" name="order_post_code"
                               placeholder="联系人地址" aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    发票类型:
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons"
                         id="OrderModelTaxType">
                        <label class="btn btn-outline-success active">
                            <input type="radio" name="order_tax_type" value="无票" autocomplete="off" checked> 无票
                        </label>
                        <label class="btn btn-outline-success">
                            <input type="radio" name="order_tax_type" value="普票" autocomplete="off"> 普票
                        </label>
                        <label class="btn btn-outline-success">
                            <input type="radio" name="order_tax_type" value="专票" autocomplete="off"> 专票
                        </label>

                    </div>
                    对方开票:
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons"
                         id="OrderModelTaxable">
                        <label class="btn btn-outline-success active">
                            <input type="radio" name="order_taxable" value="无票" autocomplete="off" checked> 无票
                        </label>
                        <label class="btn btn-outline-success">
                            <input type="radio" name="order_taxable" value="普票" autocomplete="off"> 普票
                        </label>
                        <label class="btn btn-outline-success">
                            <input type="radio" name="order_taxable" value="专票" autocomplete="off"> 专票
                        </label>
                    </div>

                    付款方式:
                    <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons"
                         id="OrderModelPayments"></div>
                    <hr/>

                    <form id="supportFileForm" enctype="multipart/form-data">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">相关文件</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="supportFiles" class="custom-file-input" id="supportFiles"
                                       multiple="multiple">
                                <label class="custom-file-label" for="inputGroupFile01">选择文件</label>
                            </div>
                        </div>
                    </form>
                    <hr/>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">添加服务</label>
                        </div>
                        <select class="custom-select" id="service">
                            @foreach($data['services'] as $services)

                                @foreach($services as $service=>$serviceId)
                                    @if(is_int($serviceId) || $serviceId=='disabled' )
                                        <option value="{{$serviceId}}"
                                                {{$serviceId}} cost="@if(key_exists($service."cost",$services)){{$services[$service."cost"]}}@endif">{{$service}}</option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-success" type="button" onclick="addService()">添加</button>
                        </div>
                    </div>


                    <div id="orderModelServiceSection"></div>
                    <textarea class="form-control" id="orderModelMemo" placeholder="订单备注"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="OrderModalServiceCount" value="0"/>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" onclick="generateOrder()">确认</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="showOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">订单明细
                        <span class="badge badge-pill badge-danger"> <span id="showOrderModalOrderId"></span></span>
                        <span class="badge badge-primary">创建: <span id="showOrderModalOrderCreatedAt"></span></span>
                        <span class="badge badge-success">最后更新: <span id="showOrderModalUpdatedAt"></span></span>
                        <span class="badge badge-secondary"><span id="showOrderModalOrderStatus"></span></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card border-primary mb-3">
                        <div class="card-header">
                            <span id="showOrderModalCompanyName">史学坤</span>
                            <span id="showOrderModalOrderCompanyTaxRef">1873****539</span>
                            <span id="showOrderModalCompanyAddress"> 吉林省白山市江源区城墙街城墙一委二组</span>
                        </div>

                        <div class="card-body">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">联系方式:</span>
                                </div>
                                <input type="text" class="form-control" id="showOrderModalContactName"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                                <input type="text" class="form-control" id="showOrderModalContactNumber"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                                <input type="text" class="form-control" id="showOrderModalContactAddress"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                                <input type="text" class="form-control" id="showOrderModalContactPostCode"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">邮寄方式:</span>
                                </div>
                                <input type="text" class="form-control" id="showOrderModalPostAddressee"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                                <input type="text" class="form-control" id="showOrderModalPostContact"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                                <input type="text" class="form-control" id="showOrderModalPostAddress"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                                <input type="text" class="form-control" id="showOrderModalPostCode"
                                       aria-label="Small" aria-describedby="inputGroup-sizing-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card border-warning mb-3">
                        <div class="card-header"><span>客户账户信息: </span>
                            <span id="showOrderModalCompanyAccount">6217 8580 0006 9847 330</span>
                            <span id="showOrderModalCompanyAccountAddress">中国银行洛阳展览路支行</span></div>
                        <div class="card-body text-success h6">
                            <div class="row">
                                <div class="col-5">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">开票类型:
                                                <span id="showOrderModalTaxType"> 普票</span>
                                            </span>
                                        </div>
                                        <input type="text" id="showOrderModalOrderTaxRef" class="form-control"
                                               placeholder="发票号码" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">收到:
                                                <span id="showOrderModalTaxable"> 专票</span>
                                            </span>
                                        </div>
                                        <input type="text" id="showOrderModalTaxNumber" class="form-control"
                                               placeholder="收到的发票号码" aria-label="Username"
                                               aria-describedby="basic-addon1">
                                        <input type="date" id="showOrderModalTaxReceivedDate" class="form-control"
                                               placeholder="收到发票日期" aria-label="Username"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <p>付款方式:
                                <span id="showOrderModalPaymentMethodName">支付宝</span>
                                <span id="showOrderModalPaymentMethodDetail"></span>
                            </p>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">工资结算:
                                    </span>
                                </div>
                                <select class="custom-select" id="showOrderModalOrderSettlement">
                                    <option value="未结算">未结算</option>
                                    <option value="已结算">已结算</option>
                                </select>
                                <input type="date" id="showOrderModalOrderSettledDate"
                                       class="form-control form-control-sm" placeholder="结算的日期" aria-label="Username"
                                       aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>

                    <div class="card border-success mb-3">
                        <div class="card-header">订单明细</div>
                        <div class="card-body text-success">
                            <div id="showOrderModalOrderDetailsList"></div>
                        </div>
                    </div>

                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">相关文件</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="showOrderModalSupportFiles"
                                   multiple="multiple">
                            <label class="custom-file-label" for="inputGroupFile01">选择文件</label>
                        </div>
                    </div>
                    <div id="showOrderModalFileList">

                    </div>


                </div>
                <div class="modal-footer">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-outline-danger" onclick="delOrder()">无效订单</button>
                        </div>
                    </div>


                    <div class="input-group mb-3">
                        <select class="form-control" id="showOrderModalNewOrderStatus">


                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="button" onclick="updateOrder()">更新订单信息
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade " tabindex="-1" role="dialog" id="companyQlfModal">
        <div class="modal-dialog" role="document" style="max-width: 1200px;">
            <div class="modal-content">
                <form id="newClientForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyQLFTitle">公司资质</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="companyQLFEMBD" style="width:1150px;height:600px;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="companyQLFFileName"/>
                        <button type="button" class="btn btn-primary" onclick="rmCompanyQLFFile()">删除</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>

        function getClientDetail(client_id) {

            layer.msg('获取中....!', {icon: 6});

            $.ajax({
                url: "{{url('admin/getClientDetail')}}",
                type: 'post',
                data: {'client_id': client_id},
                dataType: 'Json',
                success: function (data) {
                    if (!data.status) {
                        layer.msg(data.msg, {icon: data.code});
                        return false;
                    }

                    resetClientInfo();
                    $("#client_id").val(data.data.client_id);
                    var clientName = data.data.client_name == null ? "" : data.data.client_name;
                    $("#clientDetailHeader").html('(' + data.data.client_id + ') ' + clientName + ' ' + data.data.client_mobile +
                        ' <button class="btn btn-warning btn-sm " onclick="modifyClientInfo(' + data.data.client_id + ')">修改客户信息</button></span> ' +
                        '<button class="btn btn-info btn-sm " onclick="showAddCompanyModal(' + data.data.client_id + ')">添加公司</button>' +
                        ' <button class="btn btn-danger btn-sm " onclick="toPool(' + data.data.client_id + ')">放入公海</button> ' +
                        '<button class="btn btn-dark btn-sm " onclick="showOrderModel(' + data.data.client_id + ')">添加订单</button>');
                    if (!data.data.client_assign_to || (data.data.client_assign_to == "{{\Illuminate\Support\Facades\Auth::guard('admin')->user()->name}}") && data.data.client_new_enquiries != '0') {
                        $("#clientDetailHeader").append('<span id="acknowledgeButton"><button class="btn btn-success btn-sm float-right" onclick="acknowledgeClient(' + data.data.client_id + ')">认领</button></span>&nbsp;');
                    }
                    $("#clientQlf").html('');
                    $.each(data.data.qlf, function (key, qlf) {
                        var fileName = qlf.replace("client/QLF/" + data.data.client_id + "/", "");
                        $("#clientQlf").append('<span class="badge badge-info" onclick="showClientQlfFile(\'' + fileName + '\')">' + fileName + '</span> ');
                    });


                    assignClientInfo(data);
                    $("#reassignStaff").show();
                    $("#uploadClientQlf").show();
                    layer.close(layer.msg());
                }
            });
        }

        function acknowledgeClient(clientId) {
            $.ajax({
                url: "{{url('admin/acknowledgeClient')}}",
                type: 'post',
                data: {'client_id': clientId},
                dataType: 'Json',
                success: function (data) {
                    if (data.status) {
                        $("#acknowledgeButton").html('');
                        $("#newTag" + clientId).html('');
                    }
                    layer.msg(data.msg, {icon: data.code});
                    if (data.data == 'refresh') {
                        location.replace(location.href);
                    }
                }
            });

        }

        function modifyClientInfo(clientId) {
            var clientData = $("#clientDetailForm").serialize();
            clientData = decodeURIComponent(clientData, true);//解决可能出现的中文乱码问题
            clientData += '&client_id=' + clientId;

            $.ajax({
                url: "{{url('admin/modifyClientInfo')}}",
                type: 'post',
                data: clientData,
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.code == 5) {
                        $("#clientList" + clientId).attr('style', 'display:none');
                    }
                    getClientDetail(clientId);
                }
            });

        }

        function showAddCompanyModal(clientId) {
            document.getElementById('addCompanyForm').reset();
            $("#newCompanyModal").modal('show');
        }

        function addCompany() {
            var newCompanyData = new FormData($('#addCompanyForm')[0]);
            newCompanyData.append('company_client_id', $("#client_id").val());
            for (var i = 0; i < $("#company_qualification")[0].files.length; i++) {
                newCompanyData.append('file' + i, $("#company_qualification")[0].files[i]);
            }

            $.ajax({
                url: "{{url('admin/addCompany')}}",
                type: 'post',
                data: newCompanyData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.status) {
                        $("#newCompanyModal").modal('hide');
                    }
                    layer.msg(data.msg, {icon: data.code});
                    getClientDetail(newCompanyData.get('company_client_id'));


                }
            })
        }

        function addVisit() {
            var visitData = new FormData;
            visitData.append('visit_client_id', $("#client_id").val());
            visitData.append('visit_by_staff_name', "{{$data['staffName']}}");
            visitData.append('visit_status', $("#visit_status").val());
            visitData.append('visit_records', $("#visit_records").val());
            visitData.append('visit_next_date', $("#visit_visit_next_date").val());

            $.ajax({
                url: "{{url('admin/AddClientVisitData')}}",
                type: 'post',
                contentType: false,
                processData: false,
                data: visitData,
                dataType: 'Json',
                success: function (data) {
                    if (data.status) {
                        getClientDetail($("#client_id").val());
                    }
                    layer.msg(data.msg, {icon: data.code});

                }
            });


        }

        function assignClientInfo(data) {
            $("#client_enquiries").html(data.data.client_enquiries);
            $("#client_name").val(data.data.client_name);
            $("#client_address").val(data.data.client_address);
            $("#client_post_code").val(data.data.client_post_code);
            $("#client_belongs_company").val(data.data.client_belongs_company);
            $("#client_mobile").val(data.data.client_mobile);
            $("#client_tel").val(data.data.client_tel);
            $("#client_wechat").val(data.data.client_wechat);
            $("#client_qq").val(data.data.client_qq);
            $("#client_email").val(data.data.client_email);
            $("#client_source").val(data.data.client_source);
            $("#client_level").html(data.data.client_level);
            $("#created_at").html(data.data.created_at);
            $("#visit_next_date").html(data.data.client_next_date);

            $.each(data.company, function (key, company) {
                $("#companies").append('<button type="button" class="btn btn-outline-primary btn-sm" onclick="showCompany(' + company.company_id + ')" >' + company.company_name + '</button> ');
            });

            $.each(data.visit, function (key, visit) {
                $("#visitHistory").append('<tr><td>' + visit.created_at + '</td><td>' + visit.visit_status + '</td><td>' + visit.visit_records + '</td><td>' + visit.visit_next_date + '</td><td>' + visit.visit_by_staff_name + '</td></tr>');
            });

            $("#orderList").html('');
            $.each(data.orders, function (key, order) {
                let files = "";
                $.each(order.files, function (key, file) {
                    files += '<span class="badge badge-success" onclick="showOrderSupportFile(\'' + file + '\',' + order.order_id + ')">' + file + '</span> ';
                });


                $("#orderList").append('<tr><td onclick="showOrderDetail(' + JSON.stringify(order).replace(/"/g, '&quot;') + ')">' + order.order_id + '</td><td onclick="showOrderDetail(' + JSON.stringify(order).replace(/"/g, '&quot;') + ')">' + order.order_created_at + '</td><td onclick="showOrderDetail(' + JSON.stringify(order).replace(/"/g, '&quot;') + ')">' + order.order_total + '/' + order.order_profit + '</td><td onclick="showOrderDetail(' + JSON.stringify(order).replace(/"/g, '&quot;') + ')"><span class="badge badge-secondary">' + order.order_status + '</span></td><td>' + order.updated_at + '</td><td>' + files + '</td></tr>');


            });
        }

        function showCompany(companyId) {

            $.ajax({
                url: "{{url('admin/getCompanyInfo')}}",
                type: 'post',
                data: {'company_id': companyId},
                dataType: 'json',
                success: function (data) {
                    if (!data.status) {
                        layer.msg(data.msg, {icon: data.code});
                        return false;
                    }
                    $.each(data.data, function (field, value) {
                        $("#modify_" + field).val(value);
                        $("#updateCompanyModal").modal('show');
                    });

                    $("#company_qualifications").html('');
                    $.each(data.data.qlf, function (key, qlf) {
                        var fileName = qlf.replace("company/QLF/" + data.data.company_id + "/", "");
                        $("#company_qualifications").append('<span class="badge badge-info" onclick="showCompanyQlfFile(\'' + fileName + '\')">' + fileName + '</span> ');
                    });


                }
            });
        }

        function modifyCompany() {
            var companyId = $("#modify_company_id").val();
            var CompanyData = new FormData($("#updateCompanyForm")[0]);
            CompanyData.append('company_client_id', $("#client_id").val());
            for (var i = 0; i < $("#modify_company_qualification")[0].files.length; i++) {
                CompanyData.append('file' + i, $("#modify_company_qualification")[0].files[i]);
            }


            $.ajax({
                url: "{{url('admin/modifyCompany')}}",
                type: 'post',
                data: CompanyData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        $("#updateCompanyModal").modal('hide');
                        getClientDetail($("#client_id").val());
                    }

                }
            });
        }

        function toPool(clientId) {

            $.ajax({
                url: "{{url('admin/toPool')}}",
                type: 'post',
                data: {client_id: clientId},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        location.replace(location.href);
                    }
                }
            });
        }

        function showNewClientModal() {
            document.getElementById('newClientForm').reset();
            $("#newClientModal").modal('show');
        }

        function addClient() {
            var data = new FormData($("#newClientForm")[0]);
            $.ajax({
                url: "{{url('admin/newClient')}}",
                type: 'post',
                data: data,
                dataType: 'Json',
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status) {
                        $("#newClientModal").modal('hide');
                    }
                    layer.msg(data.msg, {icon: data.code});
                }
            });

        }

        function reassignClient() {
            var clientid = $("#client_id").val();
            var assign_to = $("#reassignClientTo").val();

            $.ajax({
                url: "{{url('admin/assignInfo')}}",
                type: 'post',
                data: {client_id: clientid, staff_id: assign_to, overwrite: true},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});


                    location.replace(location.href);
                }
            });
        }

        function uploadClientQualification() {
            var data = new FormData();
            for (var i = 0; i < $("#client_qualification")[0].files.length; i++) {
                data.append('file' + i, $("#client_qualification")[0].files[i]);
            }
            data.append('client_id', $("#client_id").val());

            $.ajax({
                url: "{{url('admin/clientQualificationUploads')}}",
                type: 'post',
                data: data,
                processData: false,
                contentType: false,
                async: false,
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        getClientDetail($("#client_id").val());
                    }
                }
            })
        }

        function showClientQlfFile(fileName) {

            src = '/storage/CRM/Client/QLF/';
            showFile(fileName, $("#client_id").val(), src)


        }

        function showCompanyQlfFile(fileName) {


            src = '/storage/CRM/company/QLF/';
            showFile(fileName, $("#modify_company_id").val(), src)
        }

        function showOrderSupportFile(fileName, orderId) {

            src = '/storage/CRM/Order/REF/';
            showFile(fileName, orderId, src)

        }

        function showFile(fileName, orderId, src) {
            $("#companyQLFTitle").html(fileName);
            $("#companyQLFFileName").val(fileName);
            $("#companyQLFEMBD").attr('src', src + orderId + '/' + fileName);

            var type = fileName.split('.')[1];

            if (-1 != jQuery.inArray(type, ['jpg', 'jpeg', 'pdf', 'png', 'gif'])) {

                $("#companyQlfModal").draggable();
                $("#companyQlfModal").modal('show');
            }
        }


        function rmClientQLFFile() {
            var fileName = $("#clientQLFFileName").val();
            var clientId = $("#client_id").val();
            $.ajax({
                url: "{{url('admin/rmClientQLFfile')}}",
                type: 'post',
                data: {file_name: fileName, client_id: clientId},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        getClientDetail(clientId);
                        $("#clientQlfModal").modal('hide');

                    }
                }
            });
        }

        function rmCompanyQLFFile() {
            var fileName = $("#companyQLFFileName").val();
            var companyId = $("#modify_company_id").val();
            var clientId = $("#client_id").val();
            $.ajax({
                url: "{{url('admin/rmCompanyQLFfile')}}",
                type: 'post',
                data: {file_name: fileName, company_id: companyId},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        getClientDetail(clientId);
                        showCompany(companyId);
                        $("#companyQlfModal").modal('hide');

                    }
                }
            });
        }

        function resetClientInfo() {
            $("#client_qualification").val('');
            $("#client_id").val('');
            $("#clientDetailHeader").html('');
            $("#companies").html('');
            $("#client_enquiries").html('');
            $("#client_name").val('');
            $("#client_address").val('');
            $("#client_post_code").val('');
            $("#client_belongs_company").val('');
            $("#client_mobile").val('');
            $("#client_tel").val('');
            $("#client_wechat").val('');
            $("#client_qq").val('');
            $("#client_email").val('');
            $("#client_source").val('');
            $("#client_level").html('');
            $("#created_at").html('');
            $("#visit_next_date").html('');
            $("#visitHistory").html('');
            $("#reassignStaff").hide();
            $("#uploadClientQlf").hide();
            $("#clientQLFFileName").val('');

        }

        function getStaffByDepart(departControl, staffControl) {
            var depart_id = $("#" + departControl).val();
            $.ajax({
                url: "{{url('admin/getStaffByDepart')}}",
                type: 'post',
                data: {departId: depart_id},
                dataType: 'json',
                success: function (data) {
                    if (!data.status) {
                        layer.msg(data.msg, {icon: data.code});
                        return false;
                    }
                    $("#" + staffControl).html('');
                    $.each(data.data, function (item, staff) {
                        $("#" + staffControl).append('<option value="' + staff.staff_id + '">' + staff.staff_name + '</option>');
                    });
                }
            });
        }

        function batchToPool() {

            var data = tableCheck.getData();


            layer.confirm('确认放入公海吗？', function () {
                //捉到所有被选中的，发异步进行删除

                $.ajax({
                    url: "{{url('admin/batchToPool')}}",
                    type: 'post',
                    data: {clientIds: data},
                    dataType: 'json',
                    success: function (data) {
                        layer.msg(data.msg, {icon: 1});
                        if (data.status) {
                            location.replace(location.href);
                        }
                    }

                });


            });
        }

        function showBatchToAssignModal() {


            $("#batchAssignModal").modal('show');

        }

        function batchToAssign() {

            var data = tableCheck.getData();
            //捉到所有被选中的，发异步进行删除
            var staff_id = $("#assign_staff").val() ? $("#assign_staff").val() :{{$data['staffId']}};
            $.ajax({
                url: "{{url('admin/batchToAssign')}}",
                type: 'post',
                data: {clientIds: data, staffId: staff_id},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: 1});
                    if (data.status) {
                        location.replace(location.href);
                    }
                }

            });


        }

        function showOrderModel(client_id) {
            $.ajax({
                url: "{{url('admin/getClientDetail')}}",
                type: 'post',
                data: {'client_id': client_id},
                dataType: 'Json',
                success: function (data) {
                    if (!data) {
                        lay.msg(data.msg, {icon: data.code});
                        return false;
                    }
                    $("#OrderModelTitle").html('(' + data.data.client_id + ') ' + data.data.client_name + ' 添加订单');
                    $("#OrderModelCompany").html('');
                    $.each(data.company, function (key, company) {
                        $("#OrderModelCompany").append('  <label class="btn btn-outline-success btn-sm">\n' +
                            '                                <input type="radio" name="orderClientCompany" id="orderClientCompany" autocomplete="off" value="' + company.company_id + '">' + company.company_name +
                            '                            </label>');
                    });
                    $("#OrderModelContact").html(' <label class="btn btn-outline-success btn-sm" onclick="OrderfillClientDetail(\'' + data.data.client_name + '\',\'' + data.data.client_mobile + '\',\'' + data.data.client_address + '\',\'' + data.data.client_post_code + '\')">\n' +
                        '                            <input type="checkbox" > ' + data.data.client_name +
                        '                        </label> ');


                }
            });

            $("#orderModal").modal('show');
        }

        function OrderfillClientDetail(clientName, clientMobile, clientAddress, clientPostCode) {
            $("#order_contact_name").val(clientName != "null" ? clientName : '');
            $("#order_contact_number").val(clientMobile);
            $("#order_contact_address").val(clientAddress != "null" ? clientAddress : '');
            $("#order_contact_post_code").val(clientPostCode != "null" ? clientPostCode : '');
            $("#order_post_addressee").val(clientName != "null" ? clientName : '');
            $("#order_post_contact").val(clientMobile);
            $("#order_post_address").val(clientAddress != "null" ? clientAddress : '');
            $("#order_post_code").val(clientPostCode != "null" ? clientPostCode : '');

        }

        function addService() {

            var count = Number($("#OrderModalServiceCount").val()) + 1;
            var service = $("#service").find("option:selected").text();
            var serviceId = $("#service").val();
            var cost = $("#service").find("option:selected").attr('cost');


            var serviceDiv = '<div class="input-group input-group-sm mb-3" id="service_details_' + count + '">\n' +
                '                            <div class="input-group-prepend">\n' +
                '                                <span class="input-group-text"  id="order_service_category_' + count + '">' + service + '</span>\n' +
                '<input type="hidden" id="order_service_category_id_' + count + '" value="' + serviceId + '"/>\n' +
                '                            </div>\n' +
                '                            <input type="text" class="form-control" id="order_service_name_' + count + '" placeholder="产品名称" aria-label="Small" aria-describedby="inputGroup-sizing-sm">\n' +
                '\n' +
                '                            <input type="text" class="form-control" id="order_service_price_' + count + '" placeholder="产品价格" aria-label="Small" aria-describedby="inputGroup-sizing-sm" >\n' +
                '                            <div class="input-group-prepend">\n' +
                '                                <span class="input-group-text" id="order_service_cost_' + count + '">' + cost + '</span>' +
                '                            </div>' +
                '<iframe name="iframe' + count + '" src="/storage/CRM/Order/Temp/' + serviceId + '.php"></iframe>\n' +
                '<button class="btn btn-outline-secondary btn-sm" type="button" onclick="removeService(' + count + ')">移除</button>\n' +
                '                        </div>';

            $("#orderModelServiceSection").append(serviceDiv);
            $("#OrderModalServiceCount").val(count);

        }

        function removeService(divId) {
            $("#service_details_" + divId).remove();
        }

        function getPaymentMethodByFirm(firmId) {

            $.ajax({
                url: "{{url('admin/getPaymentMethodByFirm')}}",
                type: 'post',
                data: {firm_id: firmId},
                dataType: 'json',
                success: function (data) {
                    if (!data.status) {
                        layer.msg(data.msg, {icon: data.code});
                        return false;
                    }
                    $("#OrderModelPayments").html('');
                    $.each(data.data, function (key, item) {
                        $("#OrderModelPayments").append('<label class="btn btn-outline-success ">\n' +
                            '                                <input type="radio" name="order_payment" value="' + item.payment_method_id + '" autocomplete="off" checked> ' + item.payment_method_name + '\n' +
                            '                            </label>');
                    });


                }
            });
        }

        function generateOrder() {
            var count = Number($("#OrderModalServiceCount").val());

            var services = [];
            for (var i = 1; i <= count; i++) {
                var service = {};

                if ($("#order_service_category_id_" + i).val() != undefined) {
                    var serviceId = $("#order_service_category_id_" + i).val();
                    var serviceName = $("#order_service_name_" + i).val();
                    var servicePrice = $("#order_service_price_" + i).val();
                    var iframeObj = $(window.frames["iframe" + i].document);
                    var serviceAttributes = iframeObj.find("#iframeFrom" + serviceId).serializeArray();
                    serviceAttributes = decodeURIComponent(JSON.stringify(serviceAttributes), true);
                    services.push({serviceId, serviceName, servicePrice, serviceAttributes});

                }
            }


            var data = {
                firm_id: $("#firm_id").val(),
                order_client_id: $("#client_id").val(),
                company_id: $("#orderClientCompany").val(),
                order_contact_name: $("#order_contact_name").val(),
                order_contact_number: $("#order_contact_number").val(),
                order_contact_address: $("#order_contact_address").val(),
                order_contact_post_code: $("#order_contact_post_code").val(),
                order_post_addressee: $("#order_post_addressee").val(),
                order_post_contact: $("#order_post_contact").val(),
                order_post_address: $("#order_post_address").val(),
                order_post_code: $("#order_post_code").val(),
                order_tax_type: $("input[name='order_tax_type']:checked").val(),
                order_taxable: $("input[name='order_taxable']:checked").val(),
                order_payment: $("input[name='order_payment']:checked").val(),
                order_memo: $("#orderModelMemo").val(),
                services: services,
            };


            var dd = new FormData;

            for (var i = 0; i < $("#supportFiles")[0].files.length; i++) {
                dd.append('supportFile' + i, document.getElementById('supportFiles').files[i]);
            }

            dd.append('data', JSON.stringify(data));

            $.ajax({
                url: "{{url('admin/generateOrder')}}",
                type: 'post',
                // data: {"data": data,'files':files},
                data: dd,
                processData: false,
                contentType: false,

                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if(data.status){
                        $("#orderModal").modal('hide');
                        clientOrderModalRest();
                    }
                }
            });
        }

        function showOrderDetail(order) {
            $("#showOrderModalSupportFiles").val('');
            $("#showOrderModalOrderId").text(order.order_id);
            $("#showOrderModalOrderCreatedAt").text(order.order_created_at);
            $("#showOrderModalUpdatedAt").text(order.updated_at);
            $("#showOrderModalOrderStatus").text(order.order_status);
            $("#showOrderModalCompanyName").text(order.order_company_name);
            $("#showOrderModalOrderCompanyTaxRef").text(order.order_company_tax_ref);
            $("#showOrderModalCompanyAddress").text(order.order_company_address);
            $("#showOrderModalContactName").val(order.order_contact_name);
            $("#showOrderModalContactNumber").val(order.order_contact_number);
            $("#showOrderModalContactAddress").val(order.order_contact_address);
            $("#showOrderModalContactPostCode").val(order.order_contact_post_code);
            $("#showOrderModalPostAddressee").val(order.order_post_addressee);
            $("#showOrderModalPostContact").val(order.order_post_contact);
            $("#showOrderModalPostAddress").val(order.order_post_address);
            $("#showOrderModalPostCode").val(order.order_post_code);
            $("#showOrderModalCompanyAccount").text(order.order_company_account);
            $("#showOrderModalCompanyAccountAddress").text(order.order_company_account_address);
            $("#showOrderModalTaxType").text(order.order_tax_type);
            $("#showOrderModalOrderTaxRef").val(order.order_tax_ref);
            $("#showOrderModalTaxable").html(order.order_taxable);
            $("#showOrderModalTaxNumber").val(order.tax_number);
            $("#showOrderModalTaxReceivedDate").val(order.tax_received_date);
            $("#showOrderModalPaymentMethodName").html(order.order_payment_method_name);
            $("#showOrderModalNewOrderStatus").val(order.order_status_code);

            $("#showOrderModalNewOrderStatus").html('');
            $.each(order.available_order_status, function(key,value){
                $("#showOrderModalNewOrderStatus").append('<option value="'+value.order_status_id+'">'+value.order_status_name+'</option>');
            });


            var paymentdetails = $.parseJSON(order.order_payment_method_details);
            $("#showOrderModalPaymentMethodDetail").html('');
            $.each(paymentdetails, function (key, item) {
                $("#showOrderModalPaymentMethodDetail").append('<h6><span class="badge badge-secondary">' + key + ': </span>' + item + '</h6>');
            });
            $("#showOrderModalOrderSettlement").val(order.order_settlement);
            $("#showOrderModalOrderSettledDate").val(order.order_settled_date);
            $("#showOrderModalFileList").html('');
            $.each(order.files, function (key, item) {
                $("#showOrderModalFileList").append('<span class="badge badge-success" onclick="showOrderSupportFile(\'' + item + '\',' + order.order_id + ')">' + item + '</span> ');
            });

            $("#showOrderModalOrderDetailsList").html('');
            $.each(order.carts, function (key, item) {


                var cartContents = '';


                var str = jQuery.parseJSON(item.service_attributes);

                $.each(str, function (k, v) {
                    cartContents += ' <span class="badge badge-secondary">' + v.name + ':</span><span>' + v.value + '</span>';
                });


                var content = '<div class="card border-primary mb-3">\n' +
                    '                                    <div class="card-body text-success">\n' +
                    '                                        <div class="input-group input-group-sm mb-3">\n' +
                    '                                            <div class="input-group-prepend">\n' +
                    '                                                <span class="input-group-text" id="basic-addon1">' + item.service_category + '</span>\n' +
                    '                                            </div>\n' +
                    '                                            <input type="text" id="showOrderModalServiceName" class="form-control"\n' +
                    '                                                   value="' + item.service_name + '" readonly>\n' +
                    '                                            <div class="input-group-append">\n' +
                    '                                                <span class="input-group-text"\n' +
                    '                                                      id="showOrderModalServicePrice">' + item.service_price + '</span>\n' +
                    '                                                <span class="input-group-text"\n' +
                    '                                                      id="showOrderModalServiceCost">' + item.service_cost + '</span>\n' +
                    '                                            </div>\n' +
                    '                                            <input type="text" id="showOrderModalCartServiceRef' + item.cart_id + '" class="form-control"\n' +
                    '                                                   value="' + item.service_ref + '">\n' +
                    '                                            <div class="input-group-append">\n' +
                    '                                                <button class="btn btn-outline-secondary" type="button" onclick="updateCart(' + item.cart_id + ')">更新</button>\n' +
                    '                                            </div>\n' +
                    '                                        </div>\n' +
                    cartContents +
                    '                                    </div>\n' +
                    '                                </div>';

                $("#showOrderModalOrderDetailsList").append(content);

            });

            $("#showOrderModal").draggable();
            $("#showOrderModal").modal('show');
        }

        function updateCart(cartId) {
            var cartRef = $("#showOrderModalCartServiceRef" + cartId).val();
            $.ajax({
                url: "{{url('admin/updateCartRef')}}",
                type: 'post',
                data: {cart_id: cartId, service_ref: cartRef},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                }
            });
        }

        function updateOrder() {
            var data = new FormData();
            data.append('order_id', $("#showOrderModalOrderId").text());
            data.append('order_tax_ref', $("#showOrderModalOrderTaxRef").val());
            data.append('tax_number', $("#showOrderModalTaxNumber").val());
            data.append('tax_received_date', $("#showOrderModalTaxReceivedDate").val());
            data.append('order_settlement', $("#showOrderModalOrderSettlement").val());
            data.append('order_settled_date', $("#showOrderModalOrderSettledDate").val());
            data.append('order_status_code', $("#showOrderModalNewOrderStatus").val());

            for (var i = 0; i < $("#showOrderModalSupportFiles")[0].files.length; i++) {
                data.append('file' + i, $("#showOrderModalSupportFiles")[0].files[i])
            }

            $.ajax({
                url: "{{url('admin/updateOder')}}",
                type: 'post',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        $("#showOrderModal").modal('hide');
                        getClientDetail($("#client_id").val());
                    }
                }
            });


        }

        function delOrder(){
            let orderId = $("#showOrderModalOrderId").text();
            $.ajax({
               url:"{{url('admin/delOrder')}}",
                type:'post',
                data:{order_id:orderId},
                dataType:'json',
                success:function(data){
                   layer.msg(data.msg,{icon:data.code});
                }

            });
        }

        function  clientOrderModalRest(){
            document.getElementById('orderModelForm').reset();
        }


    </script>


@endsection
