@extends('admin/layout/basic')

@section('shortcut')

@endsection

@section('content')

    <div class="row">
        <div class="col-5">
            <div class="alert alert-success">
                <h4 class="alert-heading">模板分类</h4>
                <hr>

                <div class="input-group input-group-sm ">

                    <select class="form-control" id="template_name_0">

                        @foreach($data['services'] as $services)
                            @foreach($services as $service=>$serviceId)
                            @if(is_int($serviceId) || $serviceId=='disabled')
                            <option value="{{$serviceId}}" {{$serviceId}}>{{$service}}</option>
                                @endif
                                @endforeach
                        @endforeach
                    </select>

                    <div class="input-group-append">

                        <button class="btn btn-outline-primary" type="button"
                                onclick="modify('create','template','0','template_name')">
                            添加
                        </button>

                    </div>
                </div>
                <hr>

                @foreach($data['templates'] as $template)
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="template_name_{{$template->template_id}}"
                               name="visit_status_name"
                               placeholder="信息来源" value="{{$template->template_name}}" aria-label="Recipient's username"
                               aria-describedby="basic-addon2" readonly>
                        <div class="input-group-append">


                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="modify('delete','template',{{$template->template_id}},'template_name')">
                                删除
                            </button>
                        </div>

                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="template_file_{{$template->template_id}}"
                               onchange="modify('update','template',{{$template->template_id}},'template_name')">
                        <label class="custom-file-label" for="inputGroupFile01">
                            @if($template->template_file)
                                {{$template->template_name}}.doc
                            @else
                                请合同上传模板
                            @endif
                        </label>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="order_template_file_{{$template->template_id}}"
                               onchange="modify('order','order',{{$template->template_id}},'template_name')">
                        <label class="custom-file-label" for="inputGroupFile01">
                            @if($template->template_file)
                                {{$template->template_name}}.doc
                            @else
                                请订单上传模板
                            @endif
                        </label>
                    </div>
                    <hr/>
                @endforeach


                <p class="mb-0">备注信息:这只模板的名称和上传模板文件</p>
            </div>
        </div>


    </div>


    <script>
        function modify(type, table, id, field) {

            var data = new FormData;
            if (type == "order") {

                data.append('file', document.getElementById('order_template_file_' + id).files[0]);
            } else if (type != "create") {

                data.append('file', document.getElementById('template_file_' + id).files[0]);
            } else {
                id = $("#template_name_0").val();
                var service = $("#template_name_0").find("option:selected").text();
                data.append('data', service);
            }
            data.append('type', type);
            data.append('table', table);
            data.append('id', id);
            data.append('field', field);


            $.ajax({
                url: "{{url('admin/modifyTemplate')}}",
                type: 'post',
                // data: {
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    layer.msg(data.msg, {icon: data.code});
                    location.replace(location.href);

                }

            });

        }
    </script>


@endsection
