<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MLOA-admin 1.0</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="csrf-token" content="{{csrf_token() }}">


    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

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
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>@section('title') 我的桌面@show</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <div class="layui-anim layui-anim-up">

                    <div class="x-body layui-anim layui-anim-up">
                        <blockquote class="layui-elem-quote">欢迎管理员：
                            <span class="x-red">{{$name}}</span>！当前时间: @php echo date("Y-m-d H:i:s");@endphp
                            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:-8px;float:right"
                               href="javascript:location.replace(location.href);" title="刷新">
                                <i class="layui-icon" style="line-height:30px">ဂ</i></a>
                        </blockquote>

                        @section('pSection1')@show
                        @section('pSection2')@show
                        @section('pSection3')@show
                        @section('pSection4')@show

                        <fieldset class="layui-elem-field">
                            <legend>业绩统计</legend>
                            <div class="layui-field-box">
                                <table class="layui-table">
                                    <thead>
                                    <tr>
                                        <th>姓名</th>
                                        <th>{{date('m')}}月任务</th>
                                        @php
                                            $today = date('d');
                                            for($i=1;$i<=$today;$i++){
                                                echo "<th>".sprintf("%02d", $i)."</th>";
                                            }
                                        @endphp
                                        <th>总计{{$i}}</th>
                                        <th>达成率</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>郝友伟</th>
                                        <td>40000</td>
                                    </tr>
                                    <tr>
                                        <th>总计</th>
                                        <td>500000</td>
                                        <td colspan="{{$i-1}}"></td>
                                        <td>3548</td>
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
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2018 MILUOA v1.0 All Rights Reserved</div>
</div>
<!-- 底部结束 -->
<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
</body>
</html>