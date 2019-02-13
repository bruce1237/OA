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

    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>


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
                        <blockquote class="layui-elem-quote">欢迎管理员：
                            <span class="x-red">{{$name}}</span>！当前时间: <span id="currentDateTime"></span>
                            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:-8px;float:right"
                               href="javascript:location.replace(location.href);" title="刷新">
                                <i class="layui-icon" style="line-height:30px">ဂ</i></a>
                        </blockquote>

                        @section('pSection1')@show
                        @section('pSection2')@show
                        @section('pSection3')@show
                        @section('pSection4')@show

                        <div class="alert alert-success" role="alert">
                            <h3 class="alert-heading">待办事项</h3><br/>
                            <div class="row">
                                @foreach($todoLists as $todolist)
                                    <div class="col-3">
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert"
                                             id="alert{{$todolist['id']}}">
                                            <strong>{{$todolist['event']}}</strong>
                                            <hr/>
                                            <em>{{$todolist['date']}}</em>
                                            <button type="button" class="close" onclick="delTodo({{$todolist['id']}})">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>


                        <fieldset class="layui-elem-field">
                            <legend> 今日业绩:</legend>

                            <div class="layui-field-box">
                                <div class="col-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">新增业绩:</span>
                                        </div>
                                        <input type="text" class="form-control" id="sales" placeholder="业绩"
                                               aria-label="Username" aria-describedby="basic-addon1">
                                        <div class="input-group-prepend">
                                            <button class="btn-success btn-sm" onclick="add_sales()">添加</button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </fieldset>


                        <fieldset class="layui-elem-field">
                            <legend>业绩统计</legend>
                            <div class="layui-field-box">
                                <table class="table" style="white-space:nowrap;" border=1>
                                    <thead>
                                    <tr>
                                        <th>姓名</th>
                                        <th>{{date('m')}}任务</th>
                                        @php
                                            $today = date('d');

                                            for($i=1;$i<=$today;$i++){
                                                echo "<th>".sprintf("%02d", $i)."</th>";
                                            }
                                        @endphp
                                        <th>总计</th>
                                        <th>达成率</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    @php $dailySales=[]; $grandTarget = $grandAchieved = $grandAchievedRate = $totalTarget=$totalAchieved=$toatlAchievedRate = $department_id=0;@endphp

                                    @foreach($monthlySales as $key =>$sales)


                                        @if($department_id!=0 && $department_id !=$sales['department_id'])

                                            <tr bgcolor="#deb887">
                                                <th>共计</th>
                                                <td>{{$totalTarget}}</td>
                                                <td colspan="{{$i-1}}"></td>
                                                <th>{{$totalAchieved}}</th>
                                                <th>{{$toatlAchievedRate}}%</th>
                                            </tr>

                                            @php $totalTarget=$totalAchieved=$toatlAchievedRate =0; @endphp

                                        @endif

                                        <tr bgcolor="#dcdcdc">
                                            <td>{{$sales['staff_name']}}</td>
                                            <td>{{$sales['target']}}</td>

                                            @php $b=0; $department_id =$sales['department_id'];@endphp

                                            @for($i=1;$i<=$today;$i++)
                                                @php
                                                    $a=0;
                                                    foreach ($sales['sales'] as $daySales){
                                                        if(array_key_exists(date("Y-m-").sprintf("%02d",$i), $daySales)){
                                                            $a=1; //the date is existed in the salesReport, so set up a flag to indicate there is a sales of the day
                                                            $b=0; //as the sales is existed, reset the sales flag
                                                            echo "<td bgcolor='#70ad47'>".$daySales[date("Y-m-").sprintf("%02d",$i)]."</td>";
                                                            if(array_key_exists($i,$dailySales)){
                                                                $dailySales[$i] += $daySales[date("Y-m-").sprintf("%02d",$i)];
                                                            }else{
                                                                $dailySales[$i] = $daySales[date("Y-m-").sprintf("%02d",$i)];
                                                            }
                                                        }
                                                    }
                                                if($a==0){
                                                    $b++;//the date of the sales is not existed, so add flag to change the bgcolor
                                                    if($b==1) $bgcolor = "yellow";
                                                    if($b==2) $bgcolor = "red";
                                                    if($b>=3) $bgcolor = "black";
                                                    echo "<td bgcolor=$bgcolor>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                                                }
                                                @endphp
                                            @endfor
                                            <td>{{$sales['achieved']}}</td>
                                            <td>{{round($sales['achieved']*100/$sales['target'],2)}}%</td>
                                            @php
                                                $totalTarget+=$sales['target'];
                                                $totalAchieved += $sales['achieved'];
                                                $toatlAchievedRate +=round($sales['achieved']*100/$sales['target'],2);

                                                $grandTarget +=$sales['target'];
                                                $grandAchieved += $sales['achieved'];
                                                $grandAchievedRate +=round($sales['achieved']*100/$sales['target'],2);


                                            @endphp
                                        </tr>
                                    @endforeach


                                    <tr bgcolor="#deb887">
                                        <th>共计</th>
                                        <td>{{$totalTarget}}</td>
                                        <td colspan="{{$i-1}}"></td>
                                        <th>{{$totalAchieved}}</th>
                                        <th>{{$toatlAchievedRate}}%</th>
                                    </tr>

                                    <tr bgcolor="orange">
                                        <th>总计</th>
                                        <td>{{$grandTarget}}</td>
                                        @for ($i = 1; $i <= $today; $i++)
                                            @if (array_key_exists($i, $dailySales))
                                                <td>{{$dailySales[$i]}}</td>
                                            @else
                                                <td>--</td>
                                            @endif

                                        @endfor

                                        <th>{{$grandAchieved}}</th>
                                        <th>{{$grandAchievedRate}}%</th>
                                    </tr>

                                    </tbody>
                                </table>

                            </div>
                        </fieldset>


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
                            <span class="input-group-text" id="basic-addon1">事件:</span>
                        </div>
                        <input type="text" id="a" name="event" class="form-control" placeholder="待办事件"
                               aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">日期:</span>
                        </div>
                        <input type="date" id="b" name="date" class="form-control"
                               aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveToDo()">Save changes</button>
            </div>
        </div>
    </div>
</div>


<script>


    $(function () {
        setInterval(showDateTime, 1000);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });


    function showDateTime() {
        var date = new Date();
        var dateTime = date.toLocaleString();
        $("#currentDateTime").html(dateTime);
    }


    function add_sales() {
        var sales = $("#sales").val();
        $.ajax({
            'url': "{{url('admin/addSales')}}",
            'type': 'post',
            'data': {'sales': sales},
            'dataType': 'json',
            success: function (data) {

                if (data.status) {
                    location.replace(location.href);
                }
                layer.msg(data.msg, {icon: data.icon});
            }
        });
    }

    function delTodo(todoId) {

        $.ajax({
            'url': "{{url('admin/delToDo')}}",
            'type': 'post',
            'data': {'id': todoId},
            'dataType': 'json',
            success: function (data) {
                if (data.status) {
                    $("#alert" + todoId).alert('close');
                }
                else {
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


</script>
</body>
</html>
