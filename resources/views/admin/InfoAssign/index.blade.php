@extends ('admin/layout/basic')

@section('shortcut')
    <a href="javascript:void(0);" onclick="edit_position()">导入信息Excel</a>
    <a href="javascript:void(0);" onclick="edit_position()">自动分配信息</a>
@endsection

@section('content')

    <div class="row">


        <div class="col-3">
            <div class="card border-primary mb-3">
                <div class="card-header">添加新信息</div>
                <div class="card-body text-primary">
                    <form id="newClientForm">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户隶属:</span>
                            </div>
                            <select class="form-control" name="client_belongs_company">
                                @foreach($data['firms'] as $firm)
                                    <option
                                        value="{{$firm['firm_id']}}">{{$firm['firm_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">客户来源:</span>
                            </div>
                            <select class="form-control" name="client_source">
                                @foreach($data['client_sources'] as $client_source)
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
                                <span class="input-group-text">客户手机:</span>
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

                        <input type="hidden" name="client_assign_to" value="-1"/>
                        {{--不放入公海, 也不分给任何顾问--}}
                        <button class="btn btn-outline-dark" type="reset">
                            重置
                        </button>
                        <button class="btn btn-outline-success" type="button" style="float: right;"
                                onclick="newClient()">
                            添加
                        </button>
                        <br/>&nbsp;


                    </form>
                </div>
            </div>
        </div>


        <div class="col-9">
            <div class="card border-info mb-3">
                <div class="card-header">待分配信息</div>
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <thead>
                        <tr>
                            <th scope="col">隶属</th>
                            <th scope="col">来源</th>
                            <th scope="col">电话</th>
                            <th scope="col">姓名</th>
                            <th scope="col">咨询</th>
                            <th scope="col">分配</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['pendingClients'] as $pendingClient)

                            <tr>
                                <td>{{$pendingClient['client_belongs_company']}}</td>
                                <td>{{$pendingClient['client_source']}}</td>
                                <td>{{$pendingClient['client_mobile']}}</td>
                                <td>{{$pendingClient['client_name']}}</td>
                                <td>{{$pendingClient['client_enquiries']}}</td>
                                <td>
                                    <div class="input-group input-group-sm mb-3">
                                        <select class="form-control" id="staff_id_{{$pendingClient->client_id}}"
                                                onchange="assignInfo({{$pendingClient->client_id}})">
                                            <option selected disabled>请选择</option>
                                            @foreach($data['staffs'] as $staff)
                                                <option value="{{$staff->staff_id}}">{{$staff->staff_name}}
                                                    ({{$staff->department_id}}) {{$staff->today}}
                                                    / {{$staff->month}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-8">已分配信息列表
            <div class="card border-success mb-3">
                <div class="card-header">待分配信息</div>
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <thead>
                        <tr>
                            <th scope="col">隶属</th>
                            <th scope="col">来源</th>
                            <th scope="col">电话</th>
                            <th scope="col">姓名</th>
                            <th scope="col">咨询</th>
                            <th scope="col">分配</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['freshlyAssignClients'] as $freshClient)
                            <tr>
                                <td>{{$freshClient['client_belongs_company']}}</td>
                                <td>{{$freshClient['client_source']}}</td>
                                <td>{{$freshClient['client_mobile']}}</td>
                                <td>{{$freshClient['client_name']}}</td>
                                <td>{{$freshClient['client_enquiries']}}</td>

                                <td>
                                    <div class="input-group input-group-sm mb-3">
                                        <select class="form-control" id="reAssignstaff_id_{{$freshClient->client_id}}"
                                                onchange="reAssignInfo({{$freshClient->client_id}})">
                                            @foreach($data['staffs'] as $staff)

                                                {{$select =""}}
                                                @if($staff->staff_name == $freshClient->client_assign_to)
                                                    {{$select = "selected"}}
                                                @endif
                                                <option {{$select}} value="{{$staff->staff_id}}">{{$staff->staff_name}}
                                                    ({{$staff->department_id}}) {{$staff->today}}
                                                    / {{$staff->month}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-4">信息统计表

            <div class="card border-warning mb-3">
                <div class="card-header">待分配信息</div>
                <div class="card-body text-primary">
                    <div class="input-group input-group-sm mb-3">
                        <select class="form-control" id="staffId">
                            @foreach($data['staffs'] as $staff)
                                <option value="{{$staff->staff_id}}">{{$staff->staff_name}}</option>
                            @endforeach
                        </select>

                        <div class="input-group-prepend">
                            <span class="input-group-text">开始</span>
                        </div>
                        <input class="form-control" id="startDate" type="date">

                        <div class="input-group-prepend">
                            <span class="input-group-text">结束</span>
                        </div>
                        <input class="form-control" id="endDate" type="date">

                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="searchStatic()">
                                搜索
                            </button>
                        </div>

                    </div>
                    <div id="staticResult">

                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        function newClient() {
            var data = $("#newClientForm").serialize();
            $.ajax({
                url: "{{url('admin/newClient')}}",
                type: "post",
                data: data,
                dataType: 'json',
                success: function (data) {
                    location.replace(location.href);
                    layer.msg(data.msg, {icon: data.code});
                }
            });
        }

        function reAssignInfo(clientId) {
            var staffId = $("#reAssignstaff_id_"+clientId).val();
            assign(clientId, staffId);
        }

        function assignInfo(clientId) {
            var staffId = $("#staff_id_"+clientId).val();

            assign(clientId, staffId);
        }

        function assign(clientId, staffId) {

            $.ajax({
                url: "{{url('admin/assignInfo')}}",
                type: 'post',
                data: {client_id: clientId, staff_id: staffId},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg,{icon: 1});


                    location.replace(location.href);
                }
            });
        }

        function searchStatic() {
            var staffId = $("#staffId").val();
            var startDate = $("#startDate").val() ? $("#startDate").val() : new Date().toLocaleDateString();
            var endDate = $("#endDate").val() ? $("#endDate").val() : new Date().toLocaleDateString();

            $.ajax({
                url: "{{url('admin/infoStatic')}}",
                type: 'post',
                data: {'staff_id': staffId, 'startDate': startDate, 'endDate': endDate},
                dataType: 'json',
                success: function (data) {
                    $("#staticResult").html('');
                    if(data.status){
                        $.each(data.data,function(key,item){

                              $("#staticResult").append('<div class="alert alert-primary" role="alert">'
                                  +item.info_source+':' +item.count+
                                  '</div>');

                        });

                    }else{
                        $("#staticResult").html(data.msg);
                    }
                }
            });


        }

    </script>

@endsection
