@extends('admin/layout/basic')

@section('shortcut')

@endsection

@section('content')

    <div class="row">
        <div class="col-3">
            <div class="alert alert-success">
                <h4 class="alert-heading">模板分类</h4>
                <hr>

                <div class="input-group input-group-sm ">
                    <input type="text" class="form-control" id="template_name_0" placeholder="例: 商标注册= 商标注册合同"
                           aria-label="Recipient's username" aria-describedby="basic-addon2">
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
                        <input type="text" class="form-control" id="template_name_{{$template->template_id}}" name="visit_status_name"
                               placeholder="信息来源" value="{{$template->template_name}}" aria-label="Recipient's username"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append">


                            <button class="btn btn-outline-success" type="button" id="modify_{{$template->template_id}}"
                                    onclick="modify('update','template',{{$template->template_id}},'template_name')">
                                修改
                            </button>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="modify('delete','template',{{$template->template_id}},'template_name')">
                                删除
                            </button>
                        </div>

                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="template_file_{{$template->template_id}}" onchange="$('#modify_{{$template->template_id}}').click()">
                        <label class="custom-file-label" for="inputGroupFile01">
                            @if($template->template_file)
                                {{$template->template_name}}.doc
                            @else
                                请上传模板
                            @endif
                        </label>
                    </div>
                    <hr />
                @endforeach


                <p class="mb-0">备注信息:这只模板的名称和上传模板文件</p>
            </div>
        </div>


    </div>


    <script>
        function modify(type, table, id, field) {
            var data = new FormData;
            if(type!="create"){
                data.append('file',document.getElementById('template_file_'+id).files[0]);
            }
            data.append('type',type);
            data.append('table',table);
            data.append('id',id);
            data.append('field',field);
            data.append('data',$("#" + field + '_' + id).val());


            $.ajax({
                url: "{{url('admin/modifyTemplate')}}",
                type: 'post',
                // data: {
                data:data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                   layer.msg(data.msg,{icon:data.code});
                    location.replace(location.href);

                }

            });

        }
    </script>


@endsection
