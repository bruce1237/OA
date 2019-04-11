@extends ('admin/layout/basic')

@section('shortcut')
    {{--<a href="javascript:void(0);" onclick="edit_position()">修改职位</a>--}}
@endsection

@section('content')

    <div class="row">
        <div class="col-2">
            <div class="alert alert-success">
                <h4 class="alert-heading">信息来源</h4>
                <hr>

                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" id="info_source_0" placeholder="新的信息来源"
                           aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button"
                                onclick="modify('create','info_source','0','info_source_name')">
                            添加
                        </button>

                    </div>
                </div>
                <hr>
                @foreach($data['infoSource'] as $source)
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="info_source_{{$source->info_source_id}}"
                               name="info_source_name" placeholder="信息来源" value="{{$source->info_source_name}}"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="button"
                                    onclick="modify('update','info_source', {{$source->info_source_id}},'info_source_name')">
                                修改
                            </button>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="modify('delete','info_source',{{$source->info_source_id}},'info_source_name')">
                                删除
                            </button>
                        </div>
                    </div>
                @endforeach


                <p class="mb-0">备注信息:信息来源为百度,抖音.....</p>
            </div>
        </div>

        <div class="col-2">
            <div class="alert alert-success">
                <h4 class="alert-heading">回访状态</h4>
                <hr>

                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" id="visit_status_0" placeholder="回访状态"
                           aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button"
                                onclick="modify('create','visit_status',0,'visit_status_name')">
                            添加
                        </button>

                    </div>
                </div>
                <hr>

                @foreach($data['visitStatus'] as $status)
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="visit_status_{{$status->visit_status_id}}"
                               name="visit_status_name" placeholder="信息来源" value="{{$status->visit_status_name}}"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="button"
                                    onclick="modify('update','visit_status',{{$status->visit_status_id}},'visit_status_name')">
                                修改
                            </button>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="modify('delete','visit_status',{{$status->visit_status_id}},'visit_status_name')">
                                删除
                            </button>
                        </div>
                    </div>
                @endforeach


                <p class="mb-0">备注信息:开场白, 探需求,促成交,已成交.....</p>
            </div>
        </div>

        <div class="col-2">
            <div class="alert alert-success">
                <h4 class="alert-heading">部门设置</h4>

                <hr>

                @foreach($data['departments'] as $department)
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text"
                                   for="inputGroupSelect01">{{$department->depart_name}}</label>
                        </div>
                        <select class="custom-select" id="assignable_{{$department->id}}"
                                onchange="modify('update','department',{{$department->id}},'assignable')">
                            <option selected disabled>{{$department->assignable}}</option>
                            <option value="不分配">不分配</option>
                            <option value="分配">分配</option>
                        </select>
                    </div>

                @endforeach


                <p class="mb-0">备注信息:公司接受的付款方式,微信,支付宝.....</p>
            </div>
        </div>

        <div class="col-3">
            <div class="alert alert-success">
                <h4 class="alert-heading">订单状态</h4>
                <hr>

                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" id="order_status_0" placeholder="订单状态"
                           aria-label="Recipient's username" aria-describedby="basic-addon2">

                    <div class="input-group-append">
                        <select class ="custom-select custom-select-sm" id="order_status_category_0">
                            <option value="3">状态更新</option>
                            <option value="1">合法性审批</option>
                            <option value="2">有效性审批</option>
                        </select>
                    </div>
                    <div class="input-group-append">

                        <button class="btn btn-outline-primary" type="button"
                                onclick="modify('create','order_status','0','order_status_name')">
                            添加
                        </button>

                    </div>
                </div>
                <hr>

                @foreach($data['orderStatus'] as $status)
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="order_status_{{$status->order_status_id}}"
                               name="visit_status_name" placeholder="订单状态" value="{{$status->order_status_name}}"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <select class ="custom-select custom-select-sm" id="order_status_category_{{$status->order_status_id}}">
                                <option value="{{$status->getOriginal('order_status_category')}}" selected>{{$status->order_status_category}}</option>
                                <option value="3">状态更新</option>
                                <option value="1">合法性审批</option>
                                <option value="2">有效性审批</option>
                            </select>
                            <button class="btn btn-outline-success" type="button"
                                    onclick="modify('update','order_status',{{$status->order_status_id}},'order_status_name')">
                                修改
                            </button>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="modify('delete','order_status',{{$status->order_status_id}},'order_status_name')">
                                删除
                            </button>
                        </div>
                    </div>
                @endforeach


                <p class="mb-0">备注信息:为订单处理的状态,例如商标,收到回执, 收到受理书, 已下证.....</p>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-3">

            <div class="alert alert-primary">
                <h4 class="alert-heading">业务范围</h4>
                <hr>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" id="service_0" placeholder="业务范围"
                           aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">&yen;:</label>
                    </div>
                    <input type="text" class="form-control" id="price_0" placeholder="成本价格"
                           aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <select class="custom-select custom-select-sm" id="category">

                            <option value="0" selected>无主类</option>
                            @foreach($data['service'] as $service)
                                @if($service['service_parent_id']==0)
                                    <option value="{{$service['service_id']}}">{{$service['service_name']}}</option>
                                @endif

                            @endforeach


                        </select>
                        <button class="btn btn-outline-primary" type="button"
                                onclick="modify('create','service','0','service_name')">
                            添加
                        </button>

                    </div>
                </div>
                <hr>
                @foreach($data['service'] as $service)
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="service_name_{{$service['service_id']}}"
                               name="visit_status_name" placeholder="业务名称" value="{{$service['service_name']}}"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">

                        @if($service['service_cost']!=0)
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">&yen;:</label>
                            </div>

                            <input type="text" class="form-control" id="service_cost_{{$service['service_id']}}"
                                   name="service_cost" placeholder="成本" value="{{$service['service_cost']}}"
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <input type="hidden" class="form-control" id="service_parent_id_{{$service['service_id']}}"
                                   name="service_cost" placeholder="parent_id" value="{{$service['service_parent_id']}}"
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">

                        @endif


                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="button"
                                    onclick="modify('update','service',{{$service->service_id}},'service')">
                                修改
                            </button>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="modify('delete','service',{{$service->service_id}},'service')">
                                删除
                            </button>
                        </div>
                    </div>

                @endforeach
                <p class="mb-0">备注信息:公司的所有业务.....</p>
            </div>
        </div>


        <div class="col-4">
            <div class="alert alert-primary" role="alert">
                <h4 class="alert-heading">公司信息
                    <button class="btn btn-outline-primary" data-toggle="modal" data-target="#newFirm"
                            style="float:right;">添加公司
                    </button>
                </h4>
                <hr/>
                @foreach($data['firms'] as $firm)

                    <form id="firm{{$firm['firm_id']}}" enctype="multipart/form-data" method="post" action="{{url('admin/crm_info_update')}}">
                        @csrf
                    <div class="input-group input-group-sm mb-3">
                        <h5>{{$firm['firm_name']}} </h5>&nbsp;
                        <div class="input-group-append">
                            <input type="hidden" name="firm_id" value ="{{$firm['firm_id']}}"/>
                            <button class="btn btn-outline-success" type="submit" name="type" value="update">
                                修改
                            </button>
                            <button class="btn btn-outline-secondary" type="submit" name="type" value="delete">
                                删除
                            </button>
                        </div>
                    </div>


                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司全称:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_name" value="{{$firm['firm_name']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">统一社会信用代码号:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_unique_id" value="{{$firm['firm_unique_id']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司联系人:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_contact" value="{{$firm['firm_contact']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司电话:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_tel" value="{{$firm['firm_contact']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>


                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司地址:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_address" value="{{$firm['firm_address']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司邮编:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_post_code" value="{{$firm['firm_post_code']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司账户:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_account" value="{{$firm['firm_account']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">账户地址:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_account_address" value="{{$firm['firm_account_address']}}"
                                   aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">合同章:</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="seal" id="seal" accept="image/png">
                                <label class="custom-file-label" for="inputGroupFile01">png格式的合同章</label>
                            </div>
                        </div>

                    </form>

                    <hr/>

                @endforeach

            </div>
        </div>

        <div class="col-3">
            <div class="alert alert-success">
                <h4 class="alert-heading">付款方式</h4>
                <hr/>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" id="payment_method_0" placeholder="付款方式"
                           aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <select class="custom-select custom-select-sm" id="firm_id_0">
                            @foreach($data['firms'] as $firm)
                                <option value="{{$firm['firm_id']}}">{{$firm['firm_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>

                @for($i=1;$i<6;$i++)
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="key_0_{{$i}}" placeholder="支付宝账号"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">:</label>
                        </div>
                        <input type="text" class="form-control" id="value_0_{{$i}}" placeholder="17737986930"
                               aria-label="Recipient's username" aria-describedby="basic-addon2">
                    </div>
                @endfor


                <hr/>

                <div class="input-group input-group-sm mb-3">

                    <button class="btn btn-outline-primary" type="button"
                            onclick="modify('create','payment_method','0','payment_method_name')">
                        添加
                    </button>

                </div>
                <hr>

                @foreach($data['paymentMethod'] as $payment_method)

                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control"
                               id="payment_method_{{$payment_method['payment_method_id']}}" placeholder="付款方式"
                               aria-label="Recipient's username" aria-describedby="basic-addon2"
                               value= {{$payment_method['payment_method_name']}}>
                        <div class="input-group-append">
                            <select class="custom-select custom-select-sm"
                                    id="firm_id_{{$payment_method['payment_method_id']}}">

                                @foreach($data['firms'] as $firm)
                                    @if($firm['firm_id']==$payment_method['firm_id'])
                                        <option value="{{$payment_method['firm_id']}}"
                                                selected>{{$firm['firm_name']}}</option>
                                    @else
                                        <option value="{{$firm['firm_id']}}">{{$firm['firm_name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--{{dd($data['paymentMethod'])}}--}}

                    @php $i=1; @endphp
                    @foreach($payment_method['payment_method_attributes'] as $key=>$value)

                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control"
                                   id="key_{{$payment_method['payment_method_id']}}_{{$i}}" value="{{$key}}"
                                   placeholder=""
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">:</label>
                            </div>
                            <input type="text" class="form-control"
                                   id="value_{{$payment_method['payment_method_id']}}_{{$i}}" value="{{$value}}"
                                   placeholder=""
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                        </div>
                        @php $i++; @endphp




                    @endforeach

                    @for($i;$i<6;$i++)
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control"
                                   id="key_{{$payment_method['payment_method_id']}}_{{$i}}" placeholder=""
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">:</label>
                            </div>
                            <input type="text" class="form-control"
                                   id="value_{{$payment_method['payment_method_id']}}_{{$i}}" placeholder=""
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                        </div>
                    @endfor

                    <div class="input-group input-group-sm mb-3">

                        <button class="btn btn-outline-success" type="button"
                                onclick="modify('update','payment_method','{{$payment_method['payment_method_id']}}','payment_method_name')">
                            修改
                        </button>
                        &nbsp;
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="modify('delete','payment_method','{{$payment_method['payment_method_id']}}','payment_method_name')">
                            删除
                        </button>

                    </div>
                    <hr/>
                @endforeach


                <p class="mb-0">备注信息:公司接受的付款方式,微信,支付宝.....</p>
            </div>
        </div>


    </div>





    <!-- Modal -->
    <div class="modal fade" id="newFirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="firm0" enctype="multipart/form-data" method="post" action="{{url('admin/crm_info_update')}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">添加新的公司</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司全称:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_name" aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">统一社会信用代码号:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_unique_id" aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司联系人:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_contact" aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司电话:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_tel" aria-describedby="inputGroup-sizing-sm">
                        </div>


                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司地址:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_address" aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司邮编:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_post_code" aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">公司账户:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_account" aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">账户地址:</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small"
                                   name="firm_account_address" aria-describedby="inputGroup-sizing-sm">
                        </div>

                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">公章:</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="seal" id="seal" accept="image/png">
                                <label class="custom-file-label" for="inputGroupFile01">png格式的公章图片</label>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        {{--<button type="button" class="btn btn-primary" onclick="modify('create','firm',0,'firm')">添加--}}
                        <button name="type" value ="create" type="submit" class="btn btn-primary">添加
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function modify(type, tableName, inputId, field) {

            var data = "";
            switch (field) {

                case "order_status_name":
                    data = {order_status_name: $("#order_status_"  + inputId).val(),
                            order_status_category:$("#order_status_category_"+inputId).val()};
                    break;
                case "info_source_name":
                    data = {info_source_name: $("#" + tableName + '_' + inputId).val()};
                    break;
                case "visit_status_name":
                    data = {visit_status_name: $("#" + tableName + '_' + inputId).val()};
                    break;
                case "order_type_name":
                    data = {order_type_name: $("#" + tableName + '_' + inputId).val()};
                    break;
                case "payment_method_name":


                    data = {
                        payment_method_name: $("#" + tableName + '_' + inputId).val(),
                        firm_id: $("#firm_id_" + inputId).val(),
                        key_1: $("#key_" + inputId + "_1").val(),
                        key_2: $("#key_" + inputId + "_2").val(),
                        key_3: $("#key_" + inputId + "_3").val(),
                        key_4: $("#key_" + inputId + "_4").val(),
                        key_5: $("#key_" + inputId + "_5").val(),
                        value_1: $("#value_" + inputId + "_1").val(),
                        value_2: $("#value_" + inputId + "_2").val(),
                        value_3: $("#value_" + inputId + "_3").val(),
                        value_4: $("#value_" + inputId + "_4").val(),
                        value_5: $("#value_" + inputId + "_5").val()
                    };

                    break;

                case "service_name":
                    // var cost = $("#category").val()!=0?$("#price_0").val():'-1';
                    data = {
                        // service_cost: $("#category").val() != 0 ? $("#price_0").val() : '-1',
                        service_cost: $("#category").val(),
                        service_name: $("#" + tableName + '_' + inputId).val(),
                        service_parent_id: $("#category").val()
                    };
                    break;
                case "service":
                    data = {
                        service_cost: $("#service_cost" + '_' + inputId).val(),
                        service_name: $("#" + tableName + '_name_' + inputId).val(),
                        service_parent_id: $("#service_parent_id_" + '_' + inputId).val()
                    };
                    break;
                case "firm":
                    data = $("#firm" + inputId).serialize();
                    data = decodeURIComponent(data, true);//解决可能出现的中文乱码问题
                    break;
                case "assignable":
                    data = {
                        assignable: $("#assignable_" + inputId).val(),
                    };
                    break;

                default:
                    break;
            }

            $.ajax({
                'url': "{{url('admin/crm_info_update')}}",
                'type': 'post',
                'data': {'type': type, 'tableName': tableName, 'id': inputId, 'data': JSON.stringify(data)},
                'dataType': 'json',
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    location.replace(location.href);
                }
            });
        }


    </script>

@endsection

