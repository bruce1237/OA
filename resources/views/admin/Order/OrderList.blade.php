@extends ('admin/layout/basic')
@section('shortcut')
    <a class="layui-btn layui-btn btn-success layui-btn-xs" onclick="showNewClientModal()">
        <span style="color:white"><i class="layui-icon"></i>添加新客户</span>
    </a>
@endsection

@section('content')

    <div class="row">
        <div class="col-12 alert alert-success">
            <h4>搜索订单</h4>
            <form id="orderSearchForm">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">隶属人</span>
                    </div>
                    <select class="custom-select" name="staff_id">
                        <option value="0">全部</option>
                        @foreach($data['staffs'] as $staff)
                            <option value="{{$staff->staff_id}}">{{$staff->staff_name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-prepend">
                        <span class="input-group-text">订单状态</span>
                    </div>
                    <select class="custom-select" name="order_status_code">
                        <option value="0">全部</option>
                        @foreach($data['order_stage'] as $stage)
                            <option value="{{$stage->order_status_id}}">{{$stage->order_status_name}}</option>
                        @endforeach
                    </select>

                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">客户</span>
                    </div>
                    <input type="text" class="form-control" name="client_name" aria-label="Small"
                           aria-describedby="inputGroup-sizing-sm" placeholder="客户姓名或者手机或者客户ID">

                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">产品</span>
                    </div>
                    <input type="text" class="form-control" name="service_name" aria-label="Small"
                           aria-describedby="inputGroup-sizing-sm" placeholder="商标名称,专利名称....">


                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">创建日期</span>
                    </div>
                    <input type="date" class="form-control form-control-sm" name="created_at_starts">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">-</span>
                    </div>
                    <input type="date" class="form-control form-control-sm" name="created_at_ends">

                    <div class="input-group-append">
                        <button class="btn btn-success" type="button" onclick="searchOrder()">搜索</button>
                    </div>


                </div>

            </form>
        </div>
    </div>
    <h4>搜索结果</h4>
    <div class="row">
        <div class="col-4" id="searchResultOrderList">

        </div>

        <div class="col-8" id="orderDetail" style="display: none">

            <div class="card border-primary mb-3">
                <div class="card-header">订单明细: <span id="order_id"></span>

                </div>
                <div class="card-body">
                    <div class="card border-primary mb-3">
                        <div class="card-header">客户信息:
                            创建日期:<span class="badge badge-secondary" id="created_at">Secondary</span>
                            更新时间:<span class="badge badge-secondary" id="updated_at">Secondary</span>
                        </div>
                        <div class="card-body text-success">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="order_company_name">史学坤:</span>
                                    <span class="input-group-text" id="order_company_address">史学坤:</span>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="order_company_tax_ref">史学坤:</span>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">账户信息:</span>
                                    <span class="input-group-text" id="order_company_account">史学坤:</span>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="order_company_account_address">史学坤:</span>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">联系信息:</span>
                                    <span class="input-group-text" id="order_contact_name">史学坤:</span>
                                    <span class="input-group-text" id="order_contact_number">史学坤:</span>
                                    <span class="input-group-text" id="order_contact_address">史学坤:</span>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="order_contact_post_code">史学坤:</span>
                                </div>
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">邮寄信息:</span>
                                    <span class="input-group-text" id="order_post_addressee">史学坤:</span>
                                    <span class="input-group-text" id="order_post_contact">史学坤:</span>
                                    <span class="input-group-text" id="order_post_address">史学坤:</span>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="order_post_code">史学坤:</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-warning mb-3">
                        <div class="card-header h5">财务信息</div>
                        <div class="card-body text-info">


                            <div class="input-group input-group-sm mb-3">

                                <div class="input-group-prepend">
                                    <span class="input-group-text">付款方式:</span>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="order_payment_method_name">史学坤:</span>
                                </div>

                            </div>

                            <div class="input-group input-group-sm mb-3">

                                <div class="input-group-prepend" id="order_payment_method_details">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">键</span>
                                    <span class="input-group-text" id="inputGroup-sizing-sm">值</span>
                                </div>

                            </div>


                            <div class="input-group input-group-sm mb-3">

                                <span class="input-group-text" id="inputGroup-sizing-sm">工资结算:</span>
                                <select class="custom-select" id="order_settlement">
                                    <option value="未结算">未结算</option>
                                    <option value="已结算">已结算</option>
                                </select>
                                <input type="date" id="order_settled_date" placeholder="结算日期"
                                       class="form-control form-control-sm" aria-label="Small"
                                       aria-describedby="inputGroup-sizing-sm">
                            </div>
                            <div class="input-group input-group-sm mb-3">

                                <span class="input-group-text">发票类型:</span>
                                <span class="input-group-text" id="order_tax_type">专票</span>
                                <input type="text" id="order_tax_ref" placeholder="发票号码"
                                       class="form-control form-control-sm" aria-label="Small"
                                       aria-describedby="inputGroup-sizing-sm">


                                <span class="input-group-text">对方发票:</span>
                                <span class="input-group-text" id="order_taxable">专票</span>
                                <input type="text" id="tax_number" placeholder="发票号码"
                                       class="form-control form-control-sm" aria-label="Small"
                                       aria-describedby="inputGroup-sizing-sm">
                                <input type="date" id="tax_received_date" placeholder="收到日期"
                                       class="form-control form-control-sm" aria-label="Small"
                                       aria-describedby="inputGroup-sizing-sm">

                            </div>

                        </div>
                    </div>

                    <div class="card border-success mb-3">
                        <div class="card-header">订单明细:</div>
                        <div class="card-body" id="orderDetails">

                        </div>
                    </div>

                    <div class="card border-info mb-3">
                        <div class="card-header">相关文件</div>


                        <div class="card-body text-secondary">


                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="uploadFiles" multiple>
                                    <label class="custom-file-label" for="uploadFiles">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="uploadFiles()">上传
                                    </button>
                                </div>
                            </div>

                            <div id="supportFiles"></div>


                        </div>
                    </div>
                    <div class="card border-secondary mb-3">
                        <div class="card-header">备注</div>
                        <div class="card-body text-secondary">
                            <textarea class="form-control" id="order_memo"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">


                    <div class="input-group input-group-sm col-4 float-right">
                        <select class="custom-select" id="order_status_code">

                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" onclick="updateOrder()">更新</button>
                        </div>
                    </div>


                </div>

            </div>


        </div>

    </div>


    <div class="modal fade" id="showFileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showFileModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="showFileIframe" class="embed-responsive-item"
                                src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="rmOrderFiles">删除文件</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>









    <script>
        function searchOrder() {
            layer.msg('努力搜索中,请稍候片刻哦~~');
            var Formdata = $("#orderSearchForm").serialize();

            $.ajax({
                url: "{{url('admin/orderSearch')}}",
                type: 'post',
                data: Formdata,
                dataType: 'json',
                success: function (data) {
                    if (!data.status) {
                        layer.msg(data.msg, {icon: data.code});
                        return false;
                    }
                    $("#searchResultOrderList").html('');
                    $("#searchResultOrderList").html(' <table class="table table-sm table-hover table-striped">\n' +
                        '             <thead>\n' +
                        '                <tr>\n' +
                        '                    <th>订单号</th>\n' +
                        '                    <th>客户</th>\n' +
                        '                    <th>手机</th>\n' +
                        '                    <th>隶属人</th>\n' +
                        '                    <th>创建时间</th>\n' +
                        '                    <th>订单状态</th>\n' +
                        '                </tr>\n' +
                        '             </thead>\n' +
                        '             <tbody id="searchResultOrderListTbody">\n' +
                        '             \n' +
                        '             </tbody>\n' +
                        '         </table>');

                    $.each(data.data, function (key, order) {
                        $("#searchResultOrderListTbody").append(' <tr onclick="getOrderDetails(' + order.order_id + ')">\n' +
                            '                    <td>' + order.order_id + '</td>\n' +
                            '                    <td>' + order.client_name + '</td>\n' +
                            '                    <td>' + order.client_mobile + '</td>\n' +
                            '                    <td>' + order.staff_name + '</td>\n' +
                            '                    <td>' + order.created_at + '</td>\n' +
                            '                    <td>' + order.order_status_name + '</td>\n' +
                            '                </tr>');

                    });

                }

            });
        }

        function getOrderDetails(orderId) {

            $.ajax({
                url: "{{url('admin/getOrderDetail')}}",
                type: 'post',
                data: {order_id: orderId},
                dataType: 'json',
                success: function (data) {
                    if (!data.status) {
                        layer.msg(data.msg, {icon: data.code});
                        return false;
                    }
                    orderDivRest();
                    $("#orderDetail").show();
                    $("#order_id").text(data.data.order_id);
                    $("#order_company_name").text(data.data.order_company_name);
                    $("#order_company_address").text(data.data.order_company_address);
                    $("#order_company_tax_ref").text(data.data.order_company_tax_ref);
                    $("#order_company_account").text(data.data.order_company_account);
                    $("#order_company_account_address").text(data.data.order_company_account_address);
                    $("#order_contact_name").text(data.data.order_contact_name);
                    $("#order_contact_number").text(data.data.order_contact_number);
                    $("#order_contact_address").text(data.data.order_contact_address);
                    $("#order_contact_post_code").text(data.data.order_contact_post_code);
                    $("#order_post_addressee").text(data.data.order_post_addressee);
                    $("#order_post_contact").text(data.data.order_post_contact);
                    $("#order_post_address").text(data.data.order_post_address);
                    $("#order_post_code").text(data.data.order_post_code);
                    $("#order_payment_method_name").text(data.data.order_payment_method_name);
                    $("#order_settlement").val(data.data.order_settlement);
                    $("#order_settled_date").val(data.data.order_settled_date);
                    $("#order_tax_type").text(data.data.order_tax_type);
                    $("#order_tax_ref").val(data.data.order_tax_ref);
                    $("#order_taxable").text(data.data.order_taxable);
                    $("#tax_number").val(data.data.tax_number);
                    $("#tax_received_date").val(data.data.tax_received_date);
                    $("#order_memo").text(data.data.order_memo);
                    $("#created_at").text(data.data.created_date);
                    $("#updated_at").text(data.data.updated_at);

                    $("#order_status_code").html('');
                    $("#order_status_code").append('<option value="'+data.data.order_status_code+'" selected>'+data.data.order_status_name+'</option>');
                    if(data.data.order_status_option){
                        $.each(data.data.order_status_option,function(key,item){
                            $("#order_status_code").append('<option value="'+item.order_status_id+'">'+item.order_status_name+'</option>');
                        });
                    }


                    $("#supportFiles").html('');
                    $.each(data.data.files, function (path, file) {
                        $("#supportFiles").append('<span class="badge badge-primary" onclick="showFiles(\'' + path + '\',\'' + file + '\')" >' + file + '</span> ');
                    });

                    $("#order_payment_method_details").html('');
                    let paymentDetail = JSON.parse(data.data.order_payment_method_details);
                    $.each(paymentDetail, function (key, value) {
                        $("#order_payment_method_details").append(' <span class="input-group-text" id="inputGroup-sizing-sm">' + key + ':</span>\n' +
                            '                                    <span class="input-group-text" id="inputGroup-sizing-sm">' + value + '</span>');
                    });

                    $("#orderDetails").html('');

                    $.each(data.data.carts, function (k, cart) {
                        $("#orderDetails").append('<div class="card border-primary mb-3">\n' +
                            '                                <div class="card-body text-primary">\n' +
                            '                                    <div class="input-group input-group-sm mb-3">\n' +
                            '                                        <div class="input-group-prepend">\n' +
                            '                                            <span class="input-group-text" id="service_category' + cart.cart_id + '">' + cart.service_category + '</span>\n' +
                            '                                            <span class="input-group-text" id="service_name' + cart.cart_id + '">' + cart.service_name + '</span>\n' +
                            '                                            <span class="input-group-text" id="service_price' + cart.cart_id + '">' + cart.service_price + '</span>\n' +
                            '                                            <span class="input-group-text" id="service_cost' + cart.cart_id + '">' + cart.service_cost + '</span>\n' +
                            '                                            <span class="input-group-text" id="service_profit' + cart.cart_id + '">' + cart.service_profit + '</span>\n' +
                            '                                            <input type="text" class="form-control-sm" id="service_ref' + cart.cart_id + '" value="' + cart.service_ref + '">\n' +
                            '                                        </div>\n' +
                            '                                            <input type="text" class="form-control-sm" id="service_stage' + cart.cart_id + '" value = "' + cart.service_stage + '">\n' +
                            '                                            <button type="button" class="btn btn-outline-success btn-sm"  onclick = "updateCart(' + cart.cart_id + ')">更新</button>\n' +
                            '                                    </div>\n' +
                            '                                    <div id="cartAttribute' + cart.cart_id + '">\n' +
                            '                                    </div>\n' +
                            '                                </div>\n' +
                            '                            </div>');

                        $("#cartAttribute" + cart.cart_id).html('');
                        JSON.parse(cart.service_attributes).forEach(function (value) {
                            $("#cartAttribute" + cart.cart_id).append(' <span class="badge badge-secondary">' + value.name + '</span>' + value.value);
                        });


                    });


                }
            });
        }

        function showFiles(filePath, file) {
            let src = "/storage/CRM/" + filePath;
            $("#showFileIframe").attr('src', src);
            $("#rmOrderFiles").attr('onclick', "rmOrderFile('" + filePath + "')");

            $("#showFileModalTitle").html(file);

            $("#showFileModal").draggable();
            $("#showFileModal").modal('show');
        }

        function orderDivRest() {
            $("#order_id").text('');
            $("#order_company_name").text('');
            $("#order_company_address").text('');
            $("#order_company_tax_ref").text('');
            $("#order_company_account").text('');
            $("#order_company_account_address").text('');
            $("#order_contact_name").text('');
            $("#order_contact_number").text('');
            $("#order_contact_address").text('');
            $("#order_contact_post_code").text('');
            $("#order_post_addressee").text('');
            $("#order_post_contact").text('');
            $("#order_post_address").text('');
            $("#order_post_code").text('');
            $("#order_payment_method_name").text('');
            $("#order_settlement").val('');
            $("#order_settled_date").val('');
            $("#order_tax_type").text('');
            $("#order_tax_ref").val('');
            $("#order_taxable").text('');
            $("#tax_number").val('');
            $("#tax_received_date").val('');
            $("#order_memo").text('');
            $("#supportFiles").html('');
            $("#order_payment_method_details").html('');
            $("#order_status_code").text('');
            $("#created_at").text('');
            $("#updated_at").text('');
        }

        function updateCart(cartId) {
            let cartRef = $("#service_ref" + cartId).val();
            let cartStage = $("#service_stage" + cartId).val();
            $.ajax({
                url: "{{url('admin/updateCart')}}",
                type: 'post',
                data: {cart_id: cartId, service_ref: cartRef, service_stage: cartStage},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                }
            });


        }

        function uploadFiles() {
            var order_id = $("#order_id").text();
            var data = new FormData();
            data.append('order_id', order_id);
            for (var i = 0; i < $("#uploadFiles")[0].files.length; i++) {
                data.append('file' + i, $("#uploadFiles")[0].files[i]);
            }
            $.ajax({
                url: "{{url('admin/uploadOrderSupportFiles')}}",
                type: 'post',
                data: data,
                processData: false,
                contentType: false,
                async: false,
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        getOrderDetails(order_id);
                    }
                }
            });
        }

        function rmOrderFile(file) {
            $.ajax({
                url: "{{url('admin/rmOrderFiles')}}",
                type: 'post',
                data: {filename: file},
                dataType: 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        $("#showFileModal").modal('hide');
                        getOrderDetails($("#order_id").text());
                    }
                }
            });
        }

        function updateOrder(){
            let data = new FormData();
            data.append('order_id',$("#order_id").text());
            data.append('order_settlement',$("#order_settlement").val());
            data.append('order_settled_date',$("#order_settled_date").val());
            data.append('order_tax_ref',$("#order_tax_ref").val());
            data.append('tax_number',$("#tax_number").val());
            data.append('tax_received_date',$("#tax_received_date").val());
            data.append('order_memo',$("#order_memo").val());
            data.append('order_status_code',$("#order_status_code").val());

            $.ajax({
                url: "{{url('admin/updateOrder')}}",
                type: 'post',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    if (data.status) {
                        getClientDetail($("#order_id").text());
                    }
                }
            });


        }

    </script>

@endsection
