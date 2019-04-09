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
                    <select class="custom-select custom-select-sm" name="staff_id">
                        <option value="0">全部</option>
                        @foreach($data['staffs'] as $staff)
                            <option value="{{$staff->staff_id}}">{{$staff->staff_name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-prepend">
                        <span class="input-group-text">订单状态</span>
                    </div>
                    <select class="custom-select custom-select-sm" name="order_status_code">
                        <option value="0">全部</option>
                        @foreach($data['order_stage'] as $stage)
                            <option value="{{$stage->order_status_id}}">{{$stage->order_status_name}}</option>
                        @endforeach
                    </select>

                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">客户</span>
                    </div>
                    <input type="text" class="form-control" name="client_name" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="客户姓名或者手机或者客户ID">

                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">产品</span>
                    </div>
                    <input type="text" class="form-control" name="service_name" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="商标名称,专利名称....">


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

        <div class="col-8" id="orderDetail">

        </div>

    </div>

    <script>
        function searchOrder() {
            layer.msg('努力搜索中,请稍候片刻哦~~');
            var Formdata = $("#orderSearchForm").serialize();

            $.ajax({
                url:"{{url('admin/orderSearch')}}",
                type:'post',
                data:Formdata,
                dataType:'json',
                success:function(data){
                    if(!data.status){
                        layer.msg(data.msg,{icon:data.code});
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

                    $.each(data.data,function(key,order){
                        $("#searchResultOrderListTbody").append(' <tr onclick="getOrderDetails('+ order.order_id+')">\n' +
                            '                    <td>'+ order.order_id+'</td>\n' +
                            '                    <td>'+ order.client_name+'</td>\n' +
                            '                    <td>'+ order.client_mobile+'</td>\n' +
                            '                    <td>'+ order.staff_name+'</td>\n' +
                            '                    <td>'+ order.created_at+'</td>\n' +
                            '                    <td>'+ order.order_status_name+'</td>\n' +
                            '                </tr>');

                    });

                }

            });
        }
        function getOrderDetails(orderId){
            var result=false;
            $.ajax({
                url:"{{url('admin/getOrderDetail')}}",
                type:'post',
                data:{order_id:orderId},
                dataType:'json',
                success:function(data){
                   if(!data.status){
                       layer.msg(data.msg,{icon:data.code});
                       return false;
                   }
                  result = setUpOrderDetailLayout();
                }
            });
alert(result);
        }
        function setUpOrderDetailLayout(){
            return true;
        }
    </script>

@endsection
