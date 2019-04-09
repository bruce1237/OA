<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MLOA-admin 1.0</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="csrf-token" content="{{csrf_token()}}">

    <script type="text/javascript" src="/js/jquery-3.3.1.mnin.js"></script>
    <script src="/js/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/popper.min.js"></script>


    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">


    <script src="/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/xadmin.js"></script>


</head>
<body>
<!-- 顶部开始 -->
@include('admin/layout/header')
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
@include('admin/layout/navbar')
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content" @if (!sizeof($menuList))
style="left: 0px;"
    @endif>
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>@section('title') 我的桌面@show</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">


                <div class="layui-anim layui-anim-up" style="width:auto;height:850px;overflow-x:auto;overflow-y:auto">

                    <div class="x-body layui-anim layui-anim-up">
                        <blockquote class="layui-elem-quote">欢迎{{$positionName}}： <span class="x-red">{{$name}}</span>！
                            @section('topBar')@show

                            当前时间: <span id="currentDateTime"></span>
                            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:-8px;float:right"
                               href="javascript:location.replace(location.href);" title="刷新">
                                <i class="layui-icon" style="line-height:30px">ဂ</i></a>
                        </blockquote>

                        @section('pSection1')
                            {{--代办事项--}}
                            <div class="card border-secondary mb-3">
                                <div class="card-header">待办事项: 标签
                                    <span class="badge alert-danger">今天</span>
                                    <span class="badge alert-warning">2-3天</span>
                                    <span class="badge alert-success">5天以后</span>
                                </div>
                                <div class="card-body text-secondary">
                                    <div class="row">
                                        @foreach($todoLists as $todolist)

                                            <div class="col-3 float-left" id="alert{{$todolist['id']}}">
                                                <div class="alert
                                                @php
                                                    $days =round((strtotime($todolist['date'])-strtotime(date("Y-m-d")))/(3600*24));
                                                @endphp
                                                @if($days>=5)
                                                    alert-success
                                                @elseif($days>=2 && $days<5)
                                                    alert-warning
                                                @else
                                                    alert-danger
                                                @endif

                                                    alert-dismissible fade show" role="alert">
                                                    <p class="h5">
                                                        <span
                                                            class="badge  badge-primary">{{$todolist['date']}}</span>
                                                    </p>
                                                    <p class="h4">{{$todolist['event']}}</p>
                                                    <button type="button" class="close"
                                                            onclick="delTodo({{$todolist['id']}})">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                        @show
                        @section('pSection2')

                        @show
                        @section('pSection3')

                        @show

                        @section('pSection4')
                            {{--业绩统计--}}
                            <table class="table table-striped table-sm">
                                <thead>
                                <tr class="bg-warning">
                                    <th>姓名</th>
                                    <th>任务</th>
                                    @foreach($monthlySales['workDays'] as $workDay)
                                        <th>{{$workDay}}</th>
                                    @endforeach
                                    <th>完成</th>
                                    <th>完成度</th>
                                </tr>

                                </thead>
                                <tbody>
                                @php $lastKey =0; @endphp
                                @if($monthlySales['sales'])
                                    @foreach($monthlySales['sales'] as $key=>$sale)
                                        @if($sale['new'])
                                            <tr class="bg-info">
                                                <td>合计:</td>
                                                @foreach($monthlySales['daySales'][$monthlySales['sales'][$lastKey]['department_id']] as $key=>$day)
                                                    <td>{{$day}}
                                                        @if($key =='achievedPect')
                                                            %
                                                        @endif</td>
                                                @endforeach
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>{{$sale['staff_name']}}</td>
                                            <td>{{$sale['target']}}</td>
                                            @foreach($sale['sale'] as $dailySales)
                                                @if(is_string($dailySales))
                                                    <td bgcolor="{{$dailySales}}">
                                                @else
                                                    <td bgcolor="green">{{$dailySales}}
                                                        @endif
                                                    </td>
                                                    @endforeach
                                                    <td>{{$sale['achieved']}}</td>
                                                    <td>{{$sale['achievedPect']}}%</td>
                                        </tr>

                                        @php $lastKey = $key; @endphp
                                    @endforeach
                                @endif
                                <tr class="bg-info">
                                    <td>合计:</td>
                                    @if($monthlySales['daySales'])
                                        @foreach($monthlySales['daySales'][$monthlySales['sales'][$lastKey]['department_id']] as $key=>$day)
                                            @if($day)
                                                <td>{{$day}}
                                                    @if($key =='achievedPect')
                                                        %
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="bg-primary">
                                    <td>共计:</td>
                                    @if($monthlySales['daySales'])
                                        @foreach($monthlySales['daySales']['total'] as $key=>$sale)
                                            <td>{{$sale}}
                                                @if($key =='achievedPect')
                                                    %
                                                @endif
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                                </tbody>
                            </table>

                        @show


                        <blockquote class="layui-elem-quote layui-quote-nm">本系统由米鹿科技提供技术支持。</blockquote>
                    </div>


                </div>

            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>


<!-- Modal -->

<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2018 MILUOA v1.0 All Rights Reserved</div>
</div>
<!-- 底部结束 -->

<!-- toDoModal -->
<div class="modal fade" id="toDoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">添加代办事项</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="toDoForm" class="form-inline">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">代办事件:</span>
                        </div>
                        <input type="text" id="a" name="event" class="form-control" placeholder="待办事件"
                               aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">代办日期:</span>
                        </div>
                        <input type="date" id="b" name="date" class="form-control"
                               aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="saveToDo()">添加</button>
            </div>
        </div>
    </div>
</div>


<script>


    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // notificationCheck();
        setInterval(showDateTime, 1000);
        self.setInterval("notificationCheck()", 15000);

    });


    function showDateTime() {
        var date = new Date();
        var dateTime = date.toLocaleString();
        $("#currentDateTime").html(dateTime);
    }


    function delTodo(todoId) {

        $.ajax({
            'url': "{{url('admin/delToDo')}}",
            'type': 'post',
            'data': {'id': todoId},
            'dataType': 'json',
            success: function (data) {
                if (data.status) {
                    $("#alert" + todoId).remove();

                } else {
                    layer.msg(data.msg, {icon: data.icon});
                }

            }
        });
    }

    function showToDoModal() {
        $("#toDoModal").modal('show');
    }

    function saveToDo() {


        var data = $("#toDoForm").serialize();
        $.ajax({
            'url': "{{url('admin/addToDo')}}",
            'type': 'post',
            'data': data,
            'dataType': 'json',
            success: function (data) {
                location.replace(location.href);
                layer.msg(data.msg, {icon: data.icon});
            }
        });
    }


    function notificationCheck() {

        clientChangeNotification();

    }



    function clientChangeNotification(){
        $.ajax({
            url: "{{url('admin/notificationCheck')}}",
            type: 'post',
            success: function (data) {


                    var notification = new Notification(data.title, {
                        body: data.body,
                        icon: data.icon,
                        dir: data.dir,
                        data:data.data,

                    });
                    notification.onclick=function(event){

                    }
                    notification.onshow=function(){
                        setTimeout(notification.close.bind(notification), 14000);
                    }

            }
        });
    }


</script>
</body>
</html>
