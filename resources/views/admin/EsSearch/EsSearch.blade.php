@extends('admin/layout/basic')

@section('shortcut')
    <a href="">首页</a>
    <a href="">演示</a>
    <a><cite>商标搜索</cite></a>
@endsection

@section('content')



    {{--<div class="container">--}}
    <div class="row" style="background-color: white">
        <div class="col-12">
            <div class="input-group mb-3">
                <input type="hidden" id="page" name="page" value="1"/>

                <input type="text" class="form-control" placeholder="请输入商标名称" aria-label="请输入商标名称"
                       aria-describedby="请输入商标名称" name="keyword" id="keyword" value=""
                       placeholder="Search Keyword"/>

                <div class="input-group-append">
                    <button type="button" class="btn btn-danger" onclick="search()" name="submit"
                            value="Search"> 搜索
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-1">商标分类：</div>
        <div class="col-11">
            <div class="row">
                @foreach($logoCateList as $key=>$logoCate)
                    <div class="col-2"><input type="checkbox" name="CateName" id="c{{$key}}" value="{{$logoCate->id}}"/><label
                                for="c{{$key}}">{{$logoCate->category_name}}</label></div>
                @endforeach

            </div>
        </div>

    </div>
    <div class="clearfix">
        <hr/>
    </div>

    <div class="row">
        <div class="col-1">商标类型：</div>
        <div class="col-11">
            <div class="row">
                @foreach($logoTypeList as $key=>$logoType)
                    <div class="col-2"><input type="checkbox" name="name_type" id="t{{$key}}"
                                              value="{{$logoType->id}}"/><label
                                for="t{{$key}}">{{$logoType->type}}</label></div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="clearfix">
        <hr/>
    </div>
    <div class="row">
        <div class="col-1">电商适用：</div>
        <div class="col-11">
            <div class="row">
                <div class="col-2"><input type="checkbox" name="e_type" id="d0" value="天猫"/><label
                            for="d0">天猫</label></div>
                <div class="col-2"><input type="checkbox" name="e_type" id="d1" value="京东"/><label
                            for="d1">京东</label></div>
                <div class="col-2"><input type="checkbox" name="e_type" id="d2" value="亚马逊"/><label
                            for="d2">亚马逊</label></div>
                <div class="col-2"><input type="checkbox" name="e_type" id="d3" value="聚美优品"/><label
                            for="d3">聚美优品</label></div>
                <div class="col-2"><input type="checkbox" name="e_type" id="d4" value="1号店"/><label
                            for="d4">1号店</label></div>
                <div class="col-2"><input type="checkbox" name="e_type" id="d5" value="蘑菇街"/><label
                            for="d5">蘑菇街</label></div>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <hr/>
    </div>
    <div class="row">
        <div class="col-2">字符数量：</div>
        <div class="col-10">
            <div class="row">
                @foreach($logoLengthList as $key=> $logoLength)
                    <div class="col-1"><input type="checkbox" name="name_length" id="l{{$key}}"
                                              value="{{$logoLength->id}}"/><label
                                for="l{{$key}}">{{$logoLength->name_length}}</label></div>
                @endforeach


            </div>
        </div>
    </div>
    {{--</div>--}}



    <div id="searchResult">


    </div>

    <div id="paginate"></div>


    <!-- Modal -->
    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalCenterTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_save">Save changes</button>
                </div>
            </div>
        </div>
    </div>







@endsection

<script>
    function search() {
        $("#page").val(1);
        submitForm();
    }


    function show_flow(key) {
        // // alert(searchResult['data'][key].logo_name);
        $("#ModalCenterTitle").html(searchResult['data'][key].logo_name + " 流程");
        $("#modal-body").html(searchResult['data'][key].flow_date + "----" + searchResult['data'][key].flow_data);
        $("#btn_save").hide();
        $('#ModalCenter').modal('show');
    }

    function show_goods(key) {
        $("#ModalCenterTitle").html(searchResult['data'][key].logo_name + " 适用商品");
        $("#modal-body").html(searchResult['data'][key].goods_code + "<hr />" + searchResult['data'][key].goods_name);
        // $("#btn_save").show();
        $('#ModalCenter').modal('show');
    }

    function show_all(key) {
        $("#ModalCenterTitle").html(searchResult['data'][key].logo_name+'('+searchResult['data'][key].id+')');
        $("#modal-body").html(
            '<img src="/images/logos/qd/timg.jpg" class ="img-fluid rounded"/><hr />'+
            '注册号：'+searchResult['data'][key].reg_no + '<hr />'+
            '国际分类：'+searchResult['data'][key].category_name+ '<hr />'+
            '申请日期：'+searchResult['data'][key].app_date + '<hr />'+
            '注册号/注册日期：'+searchResult['data'][key].reg_issue+' / '+searchResult['data'][key].reg_date + '<hr />'+
            '初审号/初审日期：'+searchResult['data'][key].announcement_issue+' / '+searchResult['data'][key].announcement_date + '<hr />'+
            '国际注册日期：'+searchResult['data'][key].international_date + '<hr />'+
            '后期指定日期：'+searchResult['data'][key].post_date + '<hr />'+
            '专用权期限：'+searchResult['data'][key].private_start+' / '+searchResult['data'][key].private_end + '<hr />'+
            '优先权日期：'+searchResult['data'][key].privilege_date + '<hr />'+
            '商标类型：'+searchResult['data'][key].category + '<hr />'+
            '指定颜色：'+searchResult['data'][key].color + '<hr />'+
            '注册中介：'+searchResult['data'][key].agent + '<hr />'+
            '交易类型：'+searchResult['data'][key].trade_type + '<hr />'+
            '价格：'+searchResult['data'][key].price + '<hr />'+
            '<h5>申请人信息</h5><hr/>'+
            '申请人姓名（英文）：'+searchResult['data'][key].applicant_cn+' ('+searchResult['data'][key].applicant_en + ')<hr />'+
            '申请人证件号：'+searchResult['data'][key].applicant_id + '<hr />'+
            '地址（中文）：<br/>'+searchResult['data'][key].address_cn+ '<hr />'+
            '地址（英文）：<br />'+searchResult['data'][key].address_en + '<hr />'+
            '共有申请人1：'+searchResult['data'][key].applicant_1 + '<hr />'+
            '共有申请人2：'+searchResult['data'][key].applicant_2 + '<hr />'+
            '电商适用：'+searchResult['data'][key].suitable + '<hr />'+
            '类似群：'+searchResult['data'][key].goods_code + '<hr />'+
            '商品/服务\t：'+searchResult['data'][key].goods_name + '<hr />'+
            '<h5>申请流程</h5>'+
            searchResult['data'][key].flow_date + ' ----- '+searchResult['data'][key].flow_data +'<br />'+
            searchResult['data'][key].flow_date + ' ----- '+searchResult['data'][key].flow_data +'<br />'+
            searchResult['data'][key].flow_date + ' ----- '+searchResult['data'][key].flow_data +'<br />'+
            searchResult['data'][key].flow_date + ' ----- '+searchResult['data'][key].flow_data +'<br />'+
            '<hr /><h5>代理人：</h5>'+
            '姓名：'+searchResult['data'][key].name + '<hr />'+
            '手机：'+searchResult['data'][key].mobile + '<hr />'+
            '座机：'+searchResult['data'][key].tel + '<hr />'+
            '微信：'+searchResult['data'][key].wx + '<hr />'+
            'QQ：'+searchResult['data'][key].qq + '<hr />'+
            '公司：'+searchResult['data'][key].company + '<hr />'+
            '地址：'+searchResult['data'][key].address + '<hr />'


        );
        $('#ModalCenter').modal('show');

    }


    function submitForm() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var logoName = $("#keyword").val();
        var page = $("#page").val();

        var cateName = [];
        $('input[name="CateName"]:checked').each(function () {
            cateName.push($(this).val());
        });


        var logoType = [];
        $('input[name="name_type"]:checked').each(function () {
            logoType.push($(this).val());
        });


        var eType = [];
        $('input[name="e_type"]:checked').each(function () {
            eType.push($(this).val());
        });

        var nameLength = [];//定义一个数组
        $('input[name="name_length"]:checked').each(function () {//遍历每一个名字为interest的复选框，其中选中的执行函数
            nameLength.push($(this).val());//将选中的值添加到数组chk_value中
        });


        $.ajax({
            'url': '/admin/logoEs',
            'type': 'post',
            'dataType': 'json',
            'data': {
                "logoName": logoName,
                "page": page,
                "cateName": cateName,
                "logoType": logoType,
                "eType": eType,
                "nameLength": nameLength
            },
            success: function (data) {

                if (!data['status']) {
                    alert(data['message']);
                }

                // $("#dataContainer").val(data);
                searchResult = data;


                var table = ' <xblock>\n' +
                    '        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>\n' +
                    '        <button class="layui-btn" onclick="x_admin_show(\'添加用户\',\'./order-add.html\')"><i class="layui-icon"></i>添加</button>\n' +
                    '        <span class="x-right" style="line-height:40px">共有数据：' + data['total_records'] + ' 条</span>\n' +
                    '      </xblock>' +
                    '<table class="layui-table">\n' +
                    '        <thead>\n' +
                    '          <tr>\n' +
                    '            <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>\n' +
                    '            <th>名称</th>\n' +
                    '            <th>注册号</th>\n' +
                    '            <th>分类</th>\n' +
                    '            <th>图片</th>\n' +
                    // '            <th>注册公告</th>\n' +
                    '            <th>初审公告</th>\n' +
                    '            <th width="8%">专用权期限</th>\n' +
                    // '            <th>申请信息</th>\n' +
                    '            <th>交易信息</th>\n' +
                    '            <th>联系方式</th>\n' +
                    '            <th>流程/适用商品</th>\n' +
                    '            <th >操作</th>\n' +
                    '            </tr>\n' +
                    '        </thead>\n' +
                    '        <tbody>';

                $.each(data['data'], function (key, value) {
                    //输出
                    table +=
                        '<tr>\n' +
                        '            <td>\n' +
                        '              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id=\'2\'><i class="layui-icon">&#xe605;</i></div>\n' +
                        '            </td>\n' +
                        '            <td>' + value.logo_name + '</td>\n' +
                        '            <td>' + value.reg_no + '</td>\n' +
                        '            <td>' + value.category_name + '</td>\n' +
                        // '            <td>'+value.logo_img+'</td>\n' +
                        '            <td><img src ="/images/logos/qd/image12.png"></td>\n' +
                        '            <td>' + value.reg_issue + '<br /><font size="-2">(' + value.reg_date + ')</span></td>\n' +
                        // '            <td>' + value.announcement_issue + '<br />(' + value.announcement_date + ')</td>\n' +
                        '            <td>' + value.private_start + '<br/>' + value.private_end + '</td>\n' +
                        // '            <td>' + value.applicant_cn + '<br />(' + value.app_date + ')<br/>' + value.applicant_1 + '/' + value.applicant_2 + '</td>\n' +
                        '            <td>' + value.price + '<br />(' + value.trade_type + ')' + '<br/>' + value.name + '</td>\n' +
                        '            <td>' + value.tel + '<br/>' + value.wx + '</td>\n' +
                        '            <td class="td-manage">\n' +
                        '              <a title="流程"   onclick="show_flow(' + key + ')" href="javascript:;">\n' +
                        '                <i class="layui-icon">&#xe63c;</i>\n' +
                        '              </a>\n' +
                        '              <a title="适用商品" onclick="show_goods(' + key + ')" href="javascript:;">\n' +
                        '                <i class="icon iconfont">&#xe699;</i>\n' +
                        '              </a>\n' +
                        '              <a title="全部信息" onclick="show_all(' + key + ')" href="javascript:;">\n' +
                        '                <i class="icon iconfont">&#xe696;</i></td>\n' +
                        '              </a>\n' +
                        '            <td class="td-manage">\n' +
                        '              <a title="详细信息"  onclick="x_admin_show(\'编辑\',\'order-view.html\')" href="javascript:;">\n' +
                        '                <i class="layui-icon">&#xe63c;</i>\n' +
                        '              </a>\n' +
                        '              <a title="删除" onclick="member_del(this,\'要删除的id\')" href="javascript:;">\n' +
                        '                <i class="layui-icon">&#xe640;</i>\n' +
                        '              </a>\n' +
                        '            </td>\n' +
                        '          </tr>';

                });

                table += '</tbody></table>';

                $("#searchResult").html(table);


                var ppagebtn;
                var npagebtn = "<button class='btn btn-outline-primary btn-sm' onclick='npage(" + page + ")' >Next Page</button>";
                if (page - 1) {
                    ppagebtn = "<button class='btn btn-outline-primary btn-sm' onclick='ppage(" + page + ")'>Previous Page</button> ";
                } else {
                    ppagebtn = "<button class='btn btn-outline-primary btn-sm' onclick='ppage(" + page + ")' disabled>Previous Page</button> ";
                }

                if (page >= data['pages']) {
                    npagebtn = "<button class='btn btn-outline-primary btn-sm' onclick='npage(" + page + ")' disabled>Next Page</button>";
                }


                $("#paginate").html(ppagebtn +
                    "一共 " + data['pages'] + " 页(" + page + ")" + npagebtn);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                /*弹出jqXHR对象的信息*/
                alert(jqXHR.responseText);
                alert(jqXHR.status);
                alert(jqXHR.readyState);
                alert(jqXHR.statusText);
                /*弹出其他两个参数的信息*/
                alert(textStatus);
                alert(errorThrown);
            }

        });

    }

    function ppage(page) {
        $("#page").val(page - 1);
        submitForm();

    }

    function npage(page) {
        $("#page").val(page + 1);
        submitForm();
    }


</script>

