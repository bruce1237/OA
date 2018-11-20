@extends('admin/layout/basic')

@section('shortcut')
    <button class="layui-btn layui-btn layui-btn-xs" onclick="showNewLogoModal()"><i
                class="layui-icon"></i>添加商标
    </button>
    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="showImportModal()"><i
                class="layui-icon"></i>导入商标
    </button>
    <button class="layui-btn layui-btn layui-btn-xs" onclick="x_admin_show('导入ES引擎','{{url('')}}',600,400)"><i
                class="layui-icon"></i>将商标库导入搜索引擎
    </button>


@endsection
@section('content')
    <div class="layui-row">
        <div class="layui-form layui-col-md12 x-so">

            <input type="hidden" name="page" id="pageNo" value="0"/>
            <input class="layui-input" placeholder="商标名字" name="logoName" id="logoName">
            <div class="layui-input-inline">
                <select name="logoCate" id="logoCate">
                    @foreach($category as $cate)
                        <option value="{{$cate->id}}">{{$cate->category_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="logoLength" id="logoLength">
                    @foreach($logoLength as $length)
                        <option value="{{$length->id}}">{{$length->name_length}}</option>
                    @endforeach
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="logoType" id="logoType">
                    @foreach($logoType as $type)
                        <option value="{{$type->id}}">{{$type->type}}</option>
                    @endforeach
                </select>
            </div>
            <button class="layui-btn" lay-filter="sreach" onclick="search()"><i class="layui-icon">&#xe615;</i>
            </button>

        </div>
    </div>

    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="showNewLogoModal()"><i class="layui-icon"></i>添加
        </button>
        <span id="total" class="x-right" style="line-height:40px"></span>
    </xblock>

    <div id="searchResult"></div>

    <div class="page">
        <div id="paginate"></div>
    </div>

    <div class="modal fade" id="logoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="logoModalBody">


                    <div class="row">
                        <div class="col-6 col-sm-6">
                            <img id="logo_img" src="" class="img-fluid rounded"/>
                            <input type="hidden" id="logo_img_old" value=""/>

                        </div>
                        <div class="col-6 col-sm-6">
                            <div class="input-group input-group-sm mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="logo_pic_file">
                                    <label class="custom-file-label" for="inputGroupFile04">新的商标图片</label>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">商标名称</span>
                                </div>
                                <input type="text" class="form-control" id="logo_name" aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">注册号/类别</span>
                                </div>
                                <input type="text" class="form-control" id="logo_reg" aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>
                                <select class="custom-select  custom-select-sm" id="int_cls">
                                    @foreach($category as $cate)
                                        @if($cate->id == 0)
                                            <option value="{{$cate->id}}" disabled>{{$cate->category_name}}</option>
                                        @else
                                            <option value="{{$cate->id}}">{{$cate->category_name}}</option>
                                        @endif
                                    @endforeach
                                </select>


                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">类型/颜色</span>
                                </div>

                                <select class="custom-select  custom-select-sm" id="logo_category">
                                    <option value="" selected disabled>选择类型</option>
                                    <option value="一般">一般</option>
                                    <option value="特殊">特殊</option>
                                    <option value="集体">集体</option>
                                    <option value="证明">证明</option>
                                </select>


                                <input type="text" class="form-control" id="logo_color"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">电商适用</span>
                                </div>
                                <input type="text" class="form-control" id="suitable"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">属性</label>
                                </div>
                                <select class="custom-select  custom-select-sm" id="name_type">

                                    @foreach($logoType as $type)
                                        @if($type->id == 0)
                                            <option value="{{$type->id}}" disabled>{{$type->type}}</option>
                                        @else
                                            <option value="{{$type->id}}">{{$type->type}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <select class="custom-select custom-select-sm" id="name_length">

                                    @foreach($logoLength as $length)
                                        @if($length->id == 0)
                                            <option value="{{$length->id}}" disabled>{{$length->name_length}}</option>
                                        @else
                                            <option value="{{$length->id}}">{{$length->name_length}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <select class="custom-select custom-select-sm" id="trade_type">
                                    <option value="0" selected disabled>交易类型</option>
                                    <option value="授权">授权</option>
                                    <option value="转让">转让</option>
                                </select>
                            </div>


                        </div>


                    </div>

                    <hr/>

                    <div class="row">
                        <div class="col-6">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">申请日期</span>
                                    </div>
                                    <input type="text" class="form-control" id="app_date"
                                           aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                </div>

                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">注册期号/日期</span>
                                </div>
                                <input type="text" class="form-control" id="reg_issue"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <input type="text" class="form-control" id="reg_date"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">初审期号/日期</span>
                                </div>
                                <input type="text" class="form-control" id="announcement_issue"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <input type="text" class="form-control" id="announcement_date"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">专用权日期期限</span>
                                </div>
                                <input type="text" class="form-control" id="private_start"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <input type="text" class="form-control" id="private_end"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">后期指定日期</span>
                                </div>
                                <input type="text" class="form-control" id="post_date"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">优先权日期</span>
                                </div>
                                <input type="text" class="form-control" id="privilege_date"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">国际注册日期</span>
                                </div>
                                <input type="text" class="form-control" id="international_date"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>


                        </div>
                        <div class="col-6">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">注册中介</span>
                                </div>
                                <input type="text" class="form-control" id="logo_agent"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">申请人姓名（中文）</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_cn"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">申请人姓名（英文）</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_en"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">申请人证件号</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_id"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">共有申请人</span>
                                </div>
                                <input type="text" class="form-control" id="applicant_share"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>

                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">地址（中文）</span>
                                </div>
                                <input type="text" class="form-control" id="address_cn"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">地址（英文）</span>
                                </div>
                                <input type="text" class="form-control" id="address_en"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>

                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">卖家</span>
                                </div>
                                <input type="text" class="form-control" id="seller_name"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">电话</span>
                                </div>
                                <input type="text" class="form-control" id="seller_tel"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">手机</span>
                                </div>
                                <input type="text" class="form-control" id="seller_mobile"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">微信</span>
                                </div>
                                <input type="text" class="form-control" id="seller_wx"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">价格</span>
                                </div>
                                <input type="text" class="form-control" id="logo_price"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                                <div class="input-group-append">
                                    <span class="input-group-text">元</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">地址</span>
                                </div>
                                <input type="text" class="form-control" id="seller_address"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">邮编</span>
                                </div>
                                <input type="text" class="form-control" id="seller_post_code"
                                       aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"/>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">选择一个现有卖家</span>
                                </div>
                                <select class="custom-select  custom-select-sm" id="seller_list"
                                        onchange="selectSellers()">
                                    <option value="0" selected>新建卖家</option>

                                    @foreach($logoSellers as $seller)

                                        <option value="{{$seller->id}}">{{$seller->name}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>

                    <hr/>
                    <div class="row">
                        <div class="col-12">

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">适用群组</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="goods_code"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">适用商品</span>
                                    </div>
                                    <textarea class="form-control" aria-label="With textarea"
                                              id="goods_name"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">商标流程</span>
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
                    <button type="button" class="btn btn-danger" id="del_btn" onclick="">删除</button>
                    <button type="button" class="btn btn-secondary" id="close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save_btn" onclick="newLogo()">添加</button>


                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="logoImportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoImportModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="logoImportModalBody">

                    <div class="row">

                        <div class="col-6 col-sm-6">
                            <div class="input-group input-group-sm mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="import_logo_excel">
                                    <label class="custom-file-label" for="inputGroupFile04">新的商标Excel</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-sm-6" id="logoImportModalBodyError"></div>


                </div>
                <div class="modal-footer" id="logoImportModalfoot">
                    <button type="button" class="btn btn-secondary" id="import_close_btn" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="import_btn" onclick="importLogo()">导入</button>
                </div>
            </div>
        </div>
    </div>






@endsection

<script>

    sellerList = new Object();
    var jsonStr = '<?php echo addslashes($logoSellers);?>';
    var jsonObj = JSON.parse(jsonStr);//转换为json对象
    for (var i = 0; i < jsonObj.length; i++) {
        sellerList[jsonObj[i].id] = jsonObj[i];  //取json中的值
    }


    function showImportModal() {


        $("#logoImportModalBody").html('<div class="row">\n' +
            '                        <div class="col-6 col-sm-6">\n' +
            '                            <div class="input-group input-group-sm mb-3">\n' +
            '                                <div class="custom-file">\n' +
            '                                    <input type="file" class="custom-file-input" id="import_logo_excel">\n' +
            '                                    <label class="custom-file-label" for="inputGroupFile04">新的商标Excel</label>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div> <div class="col-6 col-sm-6" id="logoImportModalBodyError"></div>');

        $("#logoImportModalfoot").html('<button type="button" class="btn btn-secondary" id="import_close_btn" data-dismiss="modal">关闭</button>\n' +
            '                    <button type="button" class="btn btn-primary" id="import_btn" onclick="importLogo()">导入</button>');
        $("#import_btn").attr('onclick','importLogo()');
        $("#import_btn").show();


        $("#logoImportModalTitle").text('导入商标excel文件');
        $("#logoImportModal").modal('show');
    }


    function importLogo() {

        // var import_logo_pics    = $("#import_logo_pics")[0].files;
        var import_logo_excel = $("#import_logo_excel")[0].files[0];
        if(!import_logo_excel){
            $("#logoImportModalBodyError").html("请选择您要上传的文件");
            exit;
        }

        var data = new FormData();
        data.append('logo_excel', import_logo_excel);
        // for(var i= 0;i<import_logo_pics.length;i++){
        //     data.append('logo_pics[]', import_logo_pics[i]);
        // }


        $("#import_btn").hide();
        $("#import_close_btn").hide();

        $("#logoImportModalBody").html('<img src="/images/timg.gif"/>');


        $.ajax({
            'url': '{{url("admin/logoImport")}}',
            'type': 'post',
            'dataType': 'Json',
            'contentType': false,
            'processData': false,
            'data': data,
            success: function (data) {
                if (data.status) {
                    $("#logoImportModalTitle").html("本次上传将会：");
                    $("#logoImportModalBody").html(data.message);
                    $("#logoImportModalfoot").html(
                        '<button  type="button" class="btn btn-secondary" id="give_up_btn" onclick ="confirmImport(0)">放弃</button>' +
                        '<button  type="button" class="btn btn-primary" id = "import_btn" onclick ="confirmImport(1)">确认</button>'
                    );

                } else {

                    $("#logoImportModalBody").html(data.message);

                }
            }

        });
    }

    function confirmImport(type) {

        $("#logoImportModalTitle").html("正在上传， 请不要关闭本页面！！");

        $("#import_btn").hide();
        $("#give_up_btn").hide();

        $("#logoImportModalBody").html('<img src="/images/timg.gif"/>');


        $.ajax({
            'url':'{{url('admin/databaseImport')}}',
            'type':'post',
            'dataType':'json',
            'data':{'data':type},
            success:function(data){
                $("#logoImportModal").modal('hide');
                if(data.status){
                    layer.msg(data.message, {icon: 1});
                }else{
                    layer.msg(data.message, {icon: 0});
                }


            }
        });
    }

    function nextPage(pageNo) {
        $("#pageNo").val(pageNo);
        searchLogo();
    }

    function prevPage(pageNo) {
        $("#pageNo").val(pageNo);
        searchLogo();
    }

    function search() {
        $("#pageNo").val(0);
        searchLogo();
    }

    function show_details(key) {
        $("#logoModalTitle").html(logos[key].logo_name + ' (id:' + logos[key].id + ')');
        // document.getElementById('file_test').files[0]
        $("#logo_pic_file").val('');

        $("#logo_id").val(logos[key].id);
        $("#seller_id").val(logos[key].seller_id);
        $("#seller_list").val(logos[key].seller_id);


        $("#logo_name").val(logos[key].logo_name);
        $("#logo_reg").val(logos[key].reg_no);
        $("#logo_color").val(logos[key].color);
        $("#logo_img_old").val(logos[key].logo_img);
        $("#logo_img").attr('src', "/images/logo_images/" + logos[key].logo_img);


        $("#int_cls").val(logos[key].int_cls);
        $("#name_type").val(logos[key].name_type);

        $("#name_length").val(logos[key].logo_length);
        $("#trade_type").val(logos[key].trade_type);


        $("#logo_category").val(logos[key].category);
        $("#suitable").val(logos[key].suitable);

        $("#app_date").val(logos[key].app_date);
        $("#reg_issue").val(logos[key].reg_issue);
        $("#reg_date").val(logos[key].reg_date);
        $("#announcement_issue").val(logos[key].announcement_issue);
        $("#announcement_date").val(logos[key].announcement_date);
        $("#private_start").val(logos[key].private_start);
        $("#private_end").val(logos[key].private_end);
        $("#post_date").val(logos[key].post_date);
        $("#privilege_date").val(logos[key].privilege_date);
        $("#international_date").val(logos[key].international_date);

        $("#logo_agent").val(logos[key].agent);
        $("#applicant_cn").val(logos[key].applicant_cn);
        $("#applicant_en").val(logos[key].applicant_en);
        $("#applicant_id").val(logos[key].applicant_id);
        $("#applicant_share").val(logos[key].applicant_share);
        $("#address_cn").val(logos[key].address_cn);
        $("#address_en").val(logos[key].address_en);


        $("#seller_name").val(logos[key].name);
        $("#seller_tel").val(logos[key].tel);
        $("#seller_mobile").val(logos[key].mobile);
        $("#seller_wx").val(logos[key].wx);
        $("#logo_price").val(logos[key].price);
        $("#seller_address").val(logos[key].address);
        $("#seller_post_code").val(logos[key].post_code);


        $("#goods_id").val(logos[key].goods_id);
        $("#flow_id").val(logos[key].flow_id);


        $("#goods_code").val(logos[key].goods_code);
        $("#goods_name").val(logos[key].goods_name);
        $("#logo_flow").val(logos[key].flow_data + '\n');

        $("#created_at").html(logos[key].created_at);
        $("#updated_at").html(logos[key].updated_at);

        $("#save_btn").text('修改').attr("onclick", "updateLogo(" + logos[key].id + ")");
        $("#del_btn").attr("onclick", "deleteLogo(" + logos[key].id + ",'" + logos[key].logo_name + "')").show();


        $("#logoModal").modal('show');
    }

    function showNewLogoModal() {
        $("#logoModalTitle").html('添加新商标');
        $("#logo_id").val('');
        $("#seller_id").val('');

        $("#logo_name").val('');
        $("#logo_reg").val('');
        $("#logo_color").val('');
        $("#logo_img_old").val('');
        $("#logo_img").attr('src', '');


        $("#int_cls").val(0);
        $("#name_type").val(0);

        $("#name_length").val(0);
        $("#trade_type").val(0);
        $("#seller_list").val(0);


        $("#logo_category").val('');
        $("#suitable").val('');

        $("#app_date").val('');
        $("#reg_issue").val('');
        $("#reg_date").val('');
        $("#announcement_issue").val('');
        $("#announcement_date").val('');
        $("#private_start").val('');
        $("#private_end").val('');
        $("#post_date").val('');
        $("#privilege_date").val('');
        $("#international_date").val('');

        $("#logo_agent").val('');
        $("#applicant_cn").val('');
        $("#applicant_en").val('');
        $("#applicant_id").val('');
        $("#applicant_share").val('');
        $("#address_cn").val('');
        $("#address_en").val('');


        $("#seller_name").val('');
        $("#seller_tel").val('');
        $("#seller_mobile").val('');
        $("#seller_wx").val('');
        $("#logo_price").val('');
        $("#seller_address").val('');
        $("#seller_post_code").val('');


        $("#goods_id").val('');
        $("#flow_id").val('');


        $("#goods_code").val('');
        $("#goods_name").val('');
        $("#logo_flow").val('');

        $("#created_at").html('');
        $("#updated_at").html('');
        $("#del_btn").hide();
        $("#save_btn").text('添加').attr('onClick', "newLogo()");


        $("#logoModal").modal('show');
    }

    function newLogo() {
        var data = getLogoModalInfo();
        $.ajax({
            'url': '/admin/logoNew',
            'type': 'POST',
            'dataType': 'json',
            'contentType': false,
            'processData': false,
            'data': data,

            success: function (data) {

                if (data.status) {

                    $("#logoModal").modal('hide');

                    layer.msg(data.message, {icon: 1});
                    setTimeout(function () {
                        location.replace(location.href);
                    }, 1000);
                }

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

    function deleteLogo(id, logo_name) {
        layer.confirm('确认要删除' + logo_name + '吗？', function (index) {
            $.ajax({
                'url': '/admin/logoDelete',
                'type': 'Delete',
                'dataType': 'json',
                'data': {'id': id},
                success: function (data) {
                    if (data.status) {
                        layer.msg(data.message, {icon: 1});
                        $("#logoModal").modal('hide');
                        searchLogo();

                    }
                }
            });
        });
    }

    function updateLogo(id) {

        var data = getLogoModalInfo(id);


        $.ajax({
            'url': '/admin/logoUpdate',
            'type': 'POST',
            'dataType': 'json',
            'contentType': false,
            'processData': false,
            'data': data,

            success: function (data) {

                if (data.status) {

                    $("#logoModal").modal('hide');
                    searchLogo();
                    layer.msg(data.message, {icon: 1});
                }

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

    function getLogoModalInfo(id) {
        var logo = new Object();
        var flows = new Object();
        var goods = new Object();
        var seller = new Object();

        logo.id = id;

        logo.reg_no = $("#logo_reg").val();
        logo.logo_img = $("#logo_img_old").val();
        logo.int_cls = $("#int_cls").val();
        logo.logo_name = $("#logo_name").val();
        logo.reg_issue = $("#reg_issue").val();
        logo.reg_date = $("#reg_date").val();
        logo.agent = $("#logo_agent").val();
        logo.app_date = $("#app_date").val();
        logo.applicant_cn = $("#applicant_cn").val();
        logo.applicant_en = $("#applicant_en").val();
        logo.applicant_id = $("#applicant_id").val();
        logo.applicant_share = $("#applicant_share").val();
        logo.address_cn = $("#address_cn").val();
        logo.address_en = $("#address_en").val();
        logo.announcement_date = $("#announcement_date").val();
        logo.announcement_issue = $("#announcement_issue").val();
        logo.international_date = $("#international_date").val();
        logo.post_date = $("#post_date").val();
        logo.private_start = $("#private_start").val();
        logo.private_end = $("#private_end").val();
        logo.privilege_date = $("#privilege_date").val();
        logo.category = $("#logo_category").val();
        logo.color = $("#logo_color").val();
        logo.trade_type = $("#trade_type").val();
        logo.price = $("#logo_price").val();
        logo.suitable = $("#suitable").val();

        logo.name_type = $("#name_type").val();
        logo.logo_length = $("#name_length").val();
        logo.seller_id = $("#seller_id").val();
        logo.goods_id = $("#goods_id").val();
        logo.flow_id = $("#flow_id").val();


        seller.name = $("#seller_name").val();
        seller.tel = $("#seller_tel").val();
        seller.wx = $("#seller_wx").val();
        seller.mobile = $("#seller_mobile").val();
        seller.address = $("#seller_address").val();


        seller.post_code = $("#seller_post_code").val();
        goods.goods_code = $("#goods_code").val();
        goods.goods_name = $("#goods_name").val();
        flows.flow_data = $("#logo_flow").val();

        var logo_pic = document.getElementById('logo_pic_file').files[0];


        var data = new FormData();
        data.append('logo_pic', logo_pic);
        data.append('logo', JSON.stringify(logo));
        data.append('goods', JSON.stringify(goods));
        data.append('flows', JSON.stringify(flows));
        data.append('seller', JSON.stringify(seller));

        return data;

    }


    function searchLogo() {
        var logoName = $("#logoName").val();
        var logoType = $("#logoType").val();
        var logoCate = $("#logoCate").val();
        var logoLength = $("#logoLength").val();
        var pageNo = Number($("#pageNo").val());


        $.ajax({
            'url': '/admin/logoSearch',
            'type': 'post',
            'dataType': 'json',
            'data': {
                'logoName': logoName,
                'logoType': logoType,
                'logoCate': logoCate,
                'logoLength': logoLength,
                "pageNo": pageNo
            },
            success: function (data) {
                if (!data.status) {
                    alert("FF");
                }

                var header = ' <table class="layui-table">\n' +
                    '        <thead>\n' +
                    '        <tr>\n' +
                    '            <th><input type="checkbox"  onclick="checkAll()" />\n' +
                    '                \n' +
                    '            </th>\n' +
                    '            <th>注册号</th>\n' +
                    '            <th>类别</th>\n' +
                    '            <th>名称</th>\n' +
                    '            <th>图片</th>\n' +
                    '            <th>专用权期限起止</th>\n' +
                    '            <th>类型</th>\n' +
                    '            <th>下单时间</th>\n' +
                    '            <th>操作</th>\n' +
                    '        </tr>\n' +
                    '        </thead>\n' +
                    '        <tbody>';
                var rows = '';
                logos = data.message;
                sellerList = data.seller_list;

                $.each(data.message, function (key, item) {
                    rows += '<tr> <td><input type="checkbox"  name="logoId" id="' + item.id + '" /></td>';

                    rows += "<td>" + item.reg_no + "</td>";
                    rows += "<td>" + item.category_name + "</td>";
                    rows += "<td>" + item.logo_name + "</td>";
                    rows += '<td><img class="img-fluid rounded" src="/images/logo_images/' + item.logo_img + '"/></td>';
                    rows += "<td>" + item.private_start + "<br/>" +
                        item.private_end + "</td>";
                    rows += "<td>" + item.category + "</td>";
                    rows += "<td>" + item.suitable + "</td>";
                    rows += '<td class="td-manage">\n' +
                        '                <a title="查看" onclick="show_details(' + key + ')" href="javascript:;">\n' +
                        '                    <i class="layui-icon">&#xe63c;</i>\n' +
                        '                </a>\n' +
                        '                <a title="删除" onclick="deleteLogo(' + item.id + ',\'' + item.logo_name + '\')" href="javascript:;">\n' +
                        '                    <i class="layui-icon">&#xe640;</i>\n' +
                        '                </a>\n' +
                        '            </td></tr>';


                });
                var footer = ' </tbody></table>';

                var fullTable = '';
                fullTable = header + rows + footer;

                // $('#total').html("共有数据：" + data.message.total + " 条");


                $("#searchResult").html(fullTable);


                var pPage;
                var nPage;
                if (pageNo >= 1) {
                    pPage = '<a class="prev" onclick="prevPage(' + (pageNo - 1) + ')"><<</a>';
                } else {
                    pPage = '';//<a class="layui-btn layui-btn-danger layui-btn-xs" >X</a>';
                }


                // var shortCut ='<input type="text" name="pno" id="pno"/> <button class="layui-btn layui-btn layui-btn-xs" onclick="goto()">goto</button>';
                var shortCut = '';


                if (data.message.length >={{$pageSize}}) {
                    nPage = '<a class="next"  onclick="nextPage(' + (pageNo + 1) + ')" >>></a>';
                } else {

                    nPage = '';
                }


                $("#paginate").html(pPage +" "+ shortCut +" "+ nPage);


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

    function selectSellers() {
        //as the id start from 1 and the array starts from 0, so use the id to minus 1 to get the array
        var new_seller_id = Number($("#seller_list").val());
        if (new_seller_id >= 0) {
            $("#seller_id").val(new_seller_id);

            $("#seller_name").val(sellerList[new_seller_id].name);
            $("#seller_tel").val(sellerList[new_seller_id].tel);
            $("#seller_mobile").val(sellerList[new_seller_id].mobile);
            $("#seller_wx").val(sellerList[new_seller_id].wx);

            $("#seller_address").val(sellerList[new_seller_id].address);
            $("#seller_post_code").val(sellerList[new_seller_id].post_code);

        } else {
            $("#seller_id").val(new_seller_id);

            $("#seller_name").val('');
            $("#seller_tel").val('');
            $("#seller_mobile").val('');
            $("#seller_wx").val('');

            $("#seller_address").val('');
            $("#seller_post_code").val('');
        }


    }

</script>