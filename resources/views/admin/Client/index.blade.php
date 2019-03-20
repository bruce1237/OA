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

@endsection


@section('content')


    <div class="row">
        <div class="col-5">
            <div class="card border-success mb-12" id="clientList">
                <div class="card-header text-white {{$data['clients']['bg']}}"
                     id="clientListTitle">{{$data['clients']['list_name']}}</div>
                <div class="card-body text-secondary" id="dataTableDiv">

                    {{--<table id="clientListTable" class="table table-sm table-hover">--}}
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
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
{{--{{dd($client)}}--}}
                            <tr id="clientList{{$client['client_id']}}" onclick="getClientDetail({{$client['client_id']}})">
                                <td>{{$client['client_name']}}@if($client['client_new_enquiries'])<span
                                        id="newTag{{$client['client_id']}}"
                                        class="badge badge-pill badge-danger">(NEW)</span>@endif</td>
                                {{--@if($client->client_new_enquiries)<span class="badge badge-pill badge-danger">(NEW)</span>@endif--}}
                                <td>{{$client['client_mobile']}}</td>
                                <td>{{$client['created_at']}}</td>
                                <td>{{$client['client_next_date']}}</td>
                                <td>{{$client['client_visit_status']}}</td>
                                <td>{{$client['client_assign_to']}}</td>
                            </tr>
                        @endforeach

                        </tbody>


                    </table>
                    {{ $data['clients']['clients']->links() }}
                </div>
            </div>


        </div>

        <div class="col-7">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">
                    <span id="clientDetailHeader"></span>

                    @if($data['staffLevel']>=env('DEPARTMENT_CHIEF_LEVEL'))

                        <div class="input-group input-group-sm mb-3" id="reassignStaff" style="display:none ">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">重新分配</span>
                            </div>
                            <select class="form-control" name="reassignClientTo" id="reassignClientTo"
                                    aria-label="Small"
                                    aria-describedby="inputGroup-sizing-sm" onchange="reassignClient()">
                                <option selected disabled>请选择业务员</option>
                                @foreach($data['assignableStaffs'] as $assignableStaff)
                                    <option
                                        value="{{$assignableStaff->staff_id}}">{{$assignableStaff->staff_name}}</option>
                                @endforeach
                            </select>
                        </div>

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
                                        <span class="input-group-text" id="inputGroup-sizing-sm"><strong>姓名</strong></span>
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
                                        <span class="input-group-text" id="inputGroup-sizing-sm">隶属公司</span>
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
                            <div class="col-2">回访日期:<span id="visit_next_date"></span></div>
                            <div class="col-2">客户等级:<span id="client_level"></span></div>
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

                <div class="card-body text-success" style="height: 300px;overflow: auto">

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

                        <select class="form-control" id="visit_status" aria-label="Small"
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
                            <input type="text" name="company_name" class="form-control" placeholder="Username"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">公司网站:</span>
                            </div>
                            <input type="text" name="company_website" class="form-control" placeholder="Username"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">纳税识别号:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" name="company_tax_id" class="form-control" placeholder="Username"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">银行账号:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" name="company_account_number" class="form-control" placeholder="Username"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">开户银行地址:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" name="company_account_address" class="form-control"
                                   placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司地址:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" name="company_address" class="form-control" placeholder="Username"
                                   aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司邮编:<i class="text-danger">*</i></span>
                            </div>
                            <input type="text" name="company_post_code" class="form-control" placeholder="Username"
                                   aria-label="Username" aria-describedby="basic-addon1">
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
                                   placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">公司网站:</span>
                            </div>
                            <input type="text" id="modify_company_website" name="company_website" class="form-control"
                                   placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">纳税识别号:</span>
                            </div>
                            <input type="text" id="modify_company_tax_id" name="company_tax_id" class="form-control"
                                   placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">银行账号:</span>
                            </div>
                            <input type="text" id="modify_company_account_number" name="company_account_number"
                                   class="form-control" placeholder="Username" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">开户银行地址:</span>
                            </div>
                            <input type="text" id="modify_company_account_address" name="company_account_address"
                                   class="form-control" placeholder="Username" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司地址:</span>
                            </div>
                            <input type="text" id="modify_company_address" name="company_address" class="form-control"
                                   placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户公司邮编:</span>
                            </div>
                            <input type="text" id="modify_company_post_code" name="company_post_code"
                                   class="form-control" placeholder="Username" aria-label="Username"
                                   aria-describedby="basic-addon1">
                        </div>

                    </form>
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

    <div class="modal fade " tabindex="-1" role="dialog" id="clientQlfModal">
        <div class="modal-dialog" role="document" style="max-width: 1200px;">
            <div class="modal-content">
                <form id="newClientForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clientQLFTitle">客户资质</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="clientQLFEMBD" style="width:1150px;height:600px;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="clientQLFFileName"/>
                        <button type="button" class="btn btn-primary" onclick="rmClientQLFFile()">删除</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" language="javascript" class="init">
        $(document).ready(function () {
            $("#clientListTable").DataTable();
        });
    </script>

    <script>


        function getClientDetail(client_id) {
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
                    $("#clientDetailHeader").html('(' + data.data.client_id + ') ' + clientName + ' ' + data.data.client_mobile + ' <button class="btn btn-warning btn-sm " onclick="modifyClientInfo(' + data.data.client_id + ')">修改客户信息</button></span> <button class="btn btn-info btn-sm " onclick="showAddCompanyModal(' + data.data.client_id + ')">添加公司</button> <button class="btn btn-danger btn-sm " onclick="toPool(' + data.data.client_id + ')">放入公海</button>');
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
                    if(data.code==5){
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
            visitData.append('visit_by_staff_name',"{{$data['staffName']}}");
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
                  if(data.status){
                      getClientDetail($("#client_id").val());
                  }
                  layer.msg(data.msg,{icon:data.code});

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
                }
            });
        }

        function modifyCompany() {
            var companyId = $("#modify_company_id").val();
            var data = new FormData($("#updateCompanyForm")[0]);
            $.ajax({
                url: "{{url('admin/modifyCompany')}}",
                type: 'post',
                data: data,
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

        function getClientList(type) {
            resetClientInfo();
            $.ajax({
                url: "{{url('admin/getClientList')}}",
                type: 'post',
                data: {type: type},
                dataType: 'json',
                success: function (data) {
                    if (!data.status) {
                        layer.msg(data.msg, {icon: data.code});
                        return false;
                    }
                    $("#clientListTitle").html(data.data.list_name);
                    $("#clientListTitle").attr('class', 'card-header text-white ' + data.data.bg);


                    var tableHeader = '  <table id="" class="table table-sm table-hover">\n' +
                        '                        <thead>\n' +
                        '                        <tr>\n' +
                        '                            <th>姓名</th>\n' +
                        '                            <th>手机</th>\n' +
                        '                            <th>创建时间</th>\n' +
                        '                            <th>回访时间</th>\n' +
                        '                            <th>销售情况</th>\n' +
                        '                            <th>隶属人</th>\n' +
                        '                        </tr>\n' +
                        '                        </thead>\n' +
                        '                        <tbody>';
                    var tableTr = '';
                    $.each(data.data.clients, function (key, client) {

                        tableTr += '<tr id="clientList' + client.client_id + '" onclick="getClientDetail(' + client.client_id + ')">';
                        var newTag = '<span id="newTag' + client.client_id + '" class="badge badge-pill badge-danger">(NEW)</span>';

                        if (client.client_new_enquiries == 0) {
                            newTag = "";
                        }
                        var clientName = client.client_name == null ? '' : client.client_name;
                        tableTr += '<td>' + clientName + newTag + '</td><td>' + client.client_mobile + '</td><td>' + client.created_at + '</td><td>' + client.visit_next_date + '</td><td>' + client.visit_status + '</td><td>' + client.client_assign_to + '</td></tr>';
                    });

                    var tableEnd = "</tr></tbody></table>";

                    $("#dataTableDiv").html(tableHeader + tableTr + tableEnd);

                    $("#clientListTable2").DataTable();
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
            $("#clientQLFTitle").html(fileName);
            $("#clientQLFFileName").val(fileName);
            $("#clientQLFEMBD").attr('src', '/storage/CRM/Client/QLF/' + $("#client_id").val() + '/' + fileName);
            $("#clientQlfModal").modal('show');
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

    </script>


@endsection
